<?php
namespace Tarikul\PersonsStore\Inc\Database;

use Tarikul\PersonsStore\Inc\Helper\Helper;

class Database
{
    private static $instance = null;
    private $wpdb;

    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Check if a table exists in the database.
     *
     * @param string $table_name
     * @return bool
     */
    public static function table_exists(string $table_name): bool
    {
        global $wpdb;
        $query = $wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->esc_like($wpdb->prefix . $table_name));
        return $wpdb->get_var($query) === $wpdb->prefix . $table_name;
    }

    /**
     * Create required tables for the plugin.
     */
    public static function create_tables(): void
    {
        global $wpdb;
        $plugin_prefix = 'ps' . '_'; // Use the defined plugin name constant

        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            "ps_profile" => "CREATE TABLE {$wpdb->prefix}{$plugin_prefix}profile (
                profile_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                title VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                address VARCHAR(255),
                zip_code VARCHAR(10),
                city VARCHAR(255),
                salary_per_month DECIMAL(10, 2),
                employee_type VARCHAR(255),
                region VARCHAR(255),
                state VARCHAR(255),
                country VARCHAR(255),
                municipality VARCHAR(255),
                department VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                product_id BIGINT(20) UNSIGNED
            ) $charset_collate;",

            "ps_reviews" => "CREATE TABLE {$wpdb->prefix}{$plugin_prefix}reviews (
                review_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                profile_id BIGINT(20) UNSIGNED NOT NULL,
                reviewer_user_id BIGINT(20) UNSIGNED NOT NULL,
                rating DECIMAL(3,2),
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (profile_id),
                INDEX (reviewer_user_id)
            ) $charset_collate;",

            "ps_review_meta" => "CREATE TABLE {$wpdb->prefix}{$plugin_prefix}review_meta (
                meta_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                review_id BIGINT(20) UNSIGNED NOT NULL,
                meta_key VARCHAR(255),
                meta_value TEXT,
                INDEX (review_id),
                INDEX (meta_key)
            ) $charset_collate;",

            "ps_email_queue" => "CREATE TABLE {$wpdb->prefix}{$plugin_prefix}email_queue (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                to_email VARCHAR(255) NOT NULL,
                subject VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            "ps_notifications" => "CREATE TABLE {$wpdb->prefix}{$plugin_prefix}notifications (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                profile_id BIGINT(20) UNSIGNED NOT NULL,
                message TEXT NOT NULL,
                status ENUM('unread', 'read') DEFAULT 'unread',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (profile_id)
            ) $charset_collate;",
        ];

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        foreach ($tables as $name => $sql) {
            if (!self::table_exists($name)) {
                dbDelta($sql);
            }
        }
    }

    /**
     * Final 
     * Insert a new user into the custom profile table.
     *
     * @param array $user_data The associative array containing user data.
     * @param int $product_id The ID of the product associated with the user.
     * @return int The ID of the newly inserted user.
     */
    public function insert_user($user_data, $product_id)
    {
        $this->wpdb->insert(
            "{$this->wpdb->prefix}ps_profile",
            array(
                'first_name' => $user_data['first_name'],
                'last_name' => $user_data['last_name'],
                'title' => $user_data['title'],
                'email' => $user_data['email'],
                'phone' => $user_data['phone'],
                'address' => $user_data['address'],
                'zip_code' => $user_data['zip_code'],
                'city' => $user_data['city'],
                'salary_per_month' => $user_data['salary_per_month'],
                'employee_type' => $user_data['employee_type'],
                'region' => $user_data['region'],
                'state' => $user_data['state'],
                'country' => $user_data['country'],
                'municipality' => $user_data['municipality'],
                'department' => $user_data['department'],
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
                'product_id' => $product_id,
            ),
            array(
                '%s', // first_name
                '%s', // last_name
                '%s', // title
                '%s', // email
                '%s', // phone
                '%s', // address
                '%s', // zip_code
                '%s', // city
                '%f', // salary_per_month (Use '%f' for decimal)
                '%s', // employee_type
                '%s', // region
                '%s', // state
                '%s', // country
                '%s', // municipality
                '%s', // department
                '%s', // created_at
                '%s', // updated_at
                '%d'  // product_id
            )
        );

        if ($this->wpdb->last_error) {
            error_log('Database error: ' . $this->wpdb->last_error);
            return false;
        }

        return $this->wpdb->insert_id;
    }

    /**
     * Final 
     * Insert a new user into the custom profile table.
     *
     * @param array $profile_id The perosn id .
     * @param int $reviewer_user_id The ID of the curren user id.
     * @return boolean|\WP_Error  The ID of the newly inserted user.
     */
    public function get_existing_review($profile_id)
    {
        $user_info = Helper::get_current_user_id_and_roles();

        if (!$user_info) {
            return new WP_Error('unauthorized', 'Unauthorized access.');
        }

        // Get reviewer ID and check if user is an administrator
        $reviewer_user_id = $user_info['id'];
        $is_admin = in_array('administrator', $user_info['roles']);

        // Restrict regular users to one review per profile
        if (!$is_admin) {
            // Check if the user has already submitted a review for this profile
            $existing_review = $this->wpdb->get_var(
                $this->wpdb->prepare(
                    "SELECT review_id FROM {$this->wpdb->prefix}ps_reviews WHERE profile_id = %d AND reviewer_user_id = %d",
                    $profile_id,
                    $reviewer_user_id
                )
            );

            // If a review already exists, return an error or a message
            if ($existing_review) {
                return true;
            }
        }

        return false;
    }

    /**
     * Insert a new review into the reviews table.
     *
     * @param int $profile_id The ID of the profile being reviewed.
     * @param int $reviewer_user_id The ID of the user who is reviewing.
     * @param float $rating The rating given by the reviewer.
     * @param string $status The status of the review ('pending', 'approved', 'rejected').
     * @return mixed|\WP_Error The ID of the newly inserted review, or a WP_Error object on failure.
     */

    public function insert_review($profile_id, $average_rating, $status = 'pending')
    {
        // TODO: current user id = $reviewer_external_profile_id
        // TODO: current user if admin then status will be approve 
        $user_info = Helper::get_current_user_id_and_roles();

        if (!$user_info) {
            die('Cheating');
        }

        // Get reviewer ID and check if user is an administrator
        $reviewer_user_id = $user_info['id'];
        $is_admin = in_array('administrator', $user_info['roles']);
        $status = $is_admin ? 'approved' : $status;

        $this->wpdb->insert(
            "{$this->wpdb->prefix}ps_reviews",
            array(
                'profile_id' => $profile_id,
                'reviewer_user_id' => $reviewer_user_id,
                'rating' => $average_rating,
                'status' => $status,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ),
            array(
                '%d', // external_profile_id
                '%d', // reviewer_external_profile_id
                '%f', // rating
                '%s', // status
                '%s', // created_at
                '%s'  // updated_at
            )
        );

        return $this->wpdb->insert_id;
    }

    /**
     * Insert meta data for a review into the review_meta table.
     *
     * @param int $review_id The ID of the review.
     * @param string $meta_key The meta key.
     * @param mixed $meta_value The meta value.
     * @return int The ID of the newly inserted review meta.
     */
    public function insert_review_meta($review_id, $meta_key, $meta_value)
    {
        $this->wpdb->insert(
            "{$this->wpdb->prefix}ps_review_meta",
            array(
                'review_id' => $review_id,
                'meta_key' => $meta_key,
                'meta_value' => maybe_serialize($meta_value)
            ),
            array(
                '%d', // review_id
                '%s', // meta_key
                '%s'  // meta_value
            )
        );

        return $this->wpdb->insert_id;
    }


    /**
     * Delete data from a table.
     *
     * @param string $table
     * @param array $where
     * @return int|false
     */
    public function delete(string $table, array $where)
    {
        $table = $this->wpdb->prefix . $table;
        return $this->wpdb->delete($table, $where);
    }

    /**
     * Update data in a table.
     *
     * @param string $table
     * @param array $data
     * @param array $where
     * @return int|false
     */
    public function update(string $table, array $data, array $where)
    {
        $table = $this->wpdb->prefix . $table;
        return $this->wpdb->update($table, $data, $where);
    }

    /**
     * Retrieve person data by profile ID.
     *
     * This function retrieves all columns from the `ps_person` table for the specified profile ID.
     * It is a dedicated function to encapsulate the logic for fetching person data based on `profile_id`.
     *
     * @param int $profile_id The ID of the profile to retrieve.
     * @return object|null The person data as an object, or null if no record is found.
     */
    public function get_person_by_id(int $profile_id)
    {
        return $this->get('ps_profile', ['profile_id' => $profile_id]);
    }


    /**
     * Get data from a table.
     *
     * @param string $table
     * @param array $where
     * @return object|null
     */
    public function get(string $table, array $where)
    {
        $sql = $this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}{$table} WHERE " . self::build_where_clause($where),
            ...array_values($where)
        );
        return $this->wpdb->get_row($sql);
    }

    /**
     * Build the WHERE clause for SQL queries.
     *
     * @param array $where
     * @return string
     */
    private function build_where_clause(array $where): string
    {
        $clauses = [];
        foreach ($where as $key => $value) {
            $clauses[] = $key . ' = %s';
        }
        return implode(' AND ', $clauses);
    }
    /**
     * Get users along with their associated review data.
     *
     * @return array|object|null
     */
    public function get_users_with_review_data()
    {
        $query = "
        SELECT 
            u.profile_id, 
            u.first_name, 
            u.last_name, 
            u.title, 
            u.email, 
            u.phone, 
            u.employee_type, 
            u.state, 
            u.municipality, 
            u.department,
            IFNULL(AVG(CASE WHEN r.status = 'approved' THEN r.rating ELSE NULL END), 0) as average_rating,
            COUNT(r.review_id) as total_reviews,
            SUM(CASE WHEN r.status = 'approved' THEN 1 ELSE 0 END) as approved_reviews,
            SUM(CASE WHEN r.status = 'pending' THEN 1 ELSE 0 END) as pending_reviews
        FROM {$this->wpdb->prefix}ps_profile u
        LEFT JOIN {$this->wpdb->prefix}ps_reviews r 
            ON u.profile_id = r.profile_id
        GROUP BY u.profile_id, u.first_name, u.last_name, u.email, u.phone, u.state, u.department
    ";
        return $this->wpdb->get_results($query);
    }

    public function get_reviews_by_external_profile_id($profile_id)
    {
        $query = $this->wpdb->prepare("
        SELECT 
            r.review_id, 
            r.rating, 
            r.status, 
            r.created_at, 
            r.updated_at
        FROM {$this->wpdb->prefix}ps_reviews r
        WHERE r.profile_id = %d
    ", $profile_id);

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function get_review_meta_by_review_id($review_id)
    {
        $query = $this->wpdb->prepare("
        SELECT 
            meta_key, 
            meta_value 
        FROM {$this->wpdb->prefix}ps_review_meta
        WHERE review_id = %d
    ", $review_id);

        return $this->wpdb->get_results($query, OBJECT_K);  // Return an associative array with meta_key as the key
    }

    /**
     * Retrieve reviews based on their status with grouped meta data.
     *
     * This function retrieves reviews with the specified status ('pending', 'approved', etc.),
     * along with their associated meta data. The meta data is returned as an associative array.
     *
     * @param string $status The status of the reviews to retrieve (e.g., 'pending', 'approved').
     * @return array An array of reviews, each containing review details and associated meta data.
     */
    public function get_reviews_by_status($status)
    {
        global $wpdb;

        // Query to get reviews based on status with grouped meta data
        $results = $wpdb->get_results($wpdb->prepare("
        SELECT 
            r.review_id,
            r.profile_id,
            r.rating,
            r.status,
            r.created_at,
            r.updated_at,
            GROUP_CONCAT(m.meta_key ORDER BY m.meta_key ASC SEPARATOR ',') AS meta_keys,
            GROUP_CONCAT(m.meta_value ORDER BY m.meta_key ASC SEPARATOR ',') AS meta_values
        FROM 
            {$wpdb->prefix}ps_reviews r
        LEFT JOIN 
            {$wpdb->prefix}ps_review_meta m ON r.review_id = m.review_id
        WHERE 
            r.status = %s
        GROUP BY 
            r.review_id
        ORDER BY 
            r.created_at DESC
    ", $status), ARRAY_A);

        // Process the results to convert meta data into an associative array
        $reviews = [];
        foreach ($results as $row) {
            $meta_keys = explode(',', $row['meta_keys']);
            $meta_values = explode(',', $row['meta_values']);
            $meta_data = array_combine($meta_keys, $meta_values);

            $reviews[] = [
                'review_id' => $row['review_id'],
                'profile_id' => $row['profile_id'],
                'rating' => $row['rating'],
                'status' => $row['status'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'meta' => $meta_data, // Converted meta data
            ];
        }

        return $reviews;
    }


    /**
     * Retrieve the full name of a person by their profile ID.
     *
     * @param int $profile_id The ID of the profile.
     * @return string|null The full name of the person (first name and last name concatenated) or null if not found.
     */
    public function get_person_name_by_id($profile_id)
    {
        global $wpdb;

        // Prepare and execute the SQL query to retrieve the first and last name based on the profile ID
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT CONCAT(first_name, ' ', last_name) AS full_name 
             FROM {$wpdb->prefix}ps_profile 
             WHERE profile_id = %d",
                $profile_id
            ),
            ARRAY_A
        );

        // Return the full name or null if the profile was not found
        return $result ? $result['full_name'] : null;
    }



    /**
     * Update person data by profile ID.
     *
     * @param int $profile_id
     * @param array $data
     * @return bool
     */
    public function update_person(int $profile_id, array $data): bool
    {
        global $wpdb;

        // Prepare data for update
        $table = $wpdb->prefix . 'ps_profile';
        $where = ['profile_id' => $profile_id];
        $format = ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'];
        $data = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'title' => $data['title'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'city' => $data['city'],
            'salary_per_month' => $data['salary_per_month'],
            'employee_type' => $data['employee_type'],
            'region' => $data['region'],
            'state' => $data['state'],
            'country' => $data['country'],
            'municipality' => $data['municipality'],
            'department' => $data['department'],
        ];

        // Perform the update
        return $wpdb->update($table, $data, $where, $format);
    }

    /**
     * Update the status of a review.
     *
     * @param int $review_id The ID of the review to update.
     * @param string $status The new status for the review.
     * @return bool True on success, false on failure.
     */
    public function update_review_status($review_id, $status)
    {
        // Define the table name
        $table = $this->wpdb->prefix . 'ps_reviews';

        // Update the review status
        $result = $this->wpdb->update(
            $table,                         // Table name
            ['status' => $status],          // Data to update
            ['review_id' => $review_id],    // Where clause
            ['%s'],                         // Data format for status
            ['%d']                          // Data format for review_id
        );

        // Check if the update was successful
        if ($result === false) {
            // Log the error if the update fails
            error_log("Failed to update review status for review ID: $review_id to status: $status. WPDB Error: " . $wpdb->last_error);
            return false;
        }

        return true;
    }


    /**
     * Get a single column value from a table based on a WHERE condition.
     *
     * @param string $table The name of the table (without prefix).
     * @param string $column The column to retrieve.
     * @param array $where The WHERE condition as an associative array.
     * @return mixed The value of the column, or false if not found.
     */
    public function get_column_value(string $table, string $column, array $where)
    {
        // Use the existing `get` method to fetch the row
        $result = $this->get($table, $where);

        // Return the specific column value if the row exists, otherwise return false
        return $result ? $result->{$column} : false;
    }


    public function delete_profile_and_related_data($profile_id)
    {
        global $wpdb;

        try {
            // Start by getting the product ID
            $product_id = $this->get_column_value('ps_profile', 'product_id', ['profile_id' => $profile_id]);

            // Optional: Begin a pseudo-transaction
            $wpdb->query('START TRANSACTION');

            // 1. Delete the WooCommerce product directly from the database if it exists
            if ($product_id) {
                $product_deleted = $wpdb->delete($wpdb->prefix . 'posts', ['ID' => $product_id, 'post_type' => 'product']);
                if ($product_deleted === false) {
                    throw new \Exception('Failed to delete WooCommerce product for profile ID: ' . $profile_id);
                }

                // You should also delete related product metadata
                $meta_deleted = $wpdb->delete($wpdb->prefix . 'postmeta', ['post_id' => $product_id]);
                if ($meta_deleted === false) {
                    throw new \Exception('Failed to delete WooCommerce product metadata for product ID: ' . $product_id);
                }

                // Optionally, delete related term relationships
                $term_relationships_deleted = $wpdb->delete($wpdb->prefix . 'term_relationships', ['object_id' => $product_id]);
                if ($term_relationships_deleted === false) {
                    throw new \Exception('Failed to delete WooCommerce product term relationships for product ID: ' . $product_id);
                }
            }

            // Start by getting the product ID
            $review_id = $this->get_column_value('ps_reviews', 'review_id', ['profile_id' => $profile_id]);

            // 2. Delete reviews associated with the profile
            $reviews_deleted = $this->delete('ps_reviews', ['profile_id' => $profile_id]);
            if ($reviews_deleted === false) {
                throw new \Exception('Failed to delete reviews for profile ID: ' . $profile_id);
            }

            // 3. Delete review meta data associated with the profile's reviews
            $review_meta_deleted = $this->delete('ps_review_meta', ['review_id' => $review_id]);
            if ($review_meta_deleted === false) {
                throw new \Exception('Failed to delete review meta data for profile ID: ' . $profile_id);
            }

            // 4. Delete the profile itself
            $profile_deleted = $this->delete('ps_profile', ['profile_id' => $profile_id]);
            if ($profile_deleted === false) {
                throw new \Exception('Failed to delete profile ID: ' . $profile_id);
            }

            // If everything is successful, commit the pseudo-transaction
            $wpdb->query('COMMIT');

            // Return success
            return true;

        } catch (\Exception $e) {
            error_log($e->getMessage());

            // Rollback all operations if any step fails
            $wpdb->query('ROLLBACK');

            // Optionally, you can manually reinsert any data that was deleted before the error occurred

            return false;
        }
    }

    // TODO: The function still not use 
    public function get_profiles_with_ratings()
    {
        global $wpdb;
        $profiles_table = $wpdb->prefix . 'ps_profile';
        $ratings_table = $wpdb->prefix . 'ps_reviews';

        $results = $wpdb->get_results("
            SELECT p.id, p.first_name, p.last_name, p.professional_title, p.department, p.municipality, 
                   COALESCE(AVG(r.rating), 0) AS average_rating
            FROM $profiles_table p
            LEFT JOIN $ratings_table r ON p.id = r.person_id
            GROUP BY p.id
        ");

        return $results;
    }
    /**
     * We get single profile avarage rating 
     * @param mixed $profile_id
     * @return mixed
     */
    public function get_profile_average_rating($profile_id)
    {
        $profile_id = absint($profile_id);

        if (!$profile_id) {
            return false; // Return false if the profile ID is invalid
        }

        $query = "
        SELECT 
            IFNULL(AVG(CASE WHEN r.status = 'approved' THEN r.rating ELSE NULL END), 0) as average_rating
        FROM {$this->wpdb->prefix}ps_reviews r
        WHERE r.profile_id = %d
    ";

        // Run the query and return just the average rating
        $average_rating = $this->wpdb->get_var($this->wpdb->prepare($query, $profile_id));

        // Format the average rating to 2 decimal places
        return number_format((float) $average_rating, 2, '.', '');
    }
}
