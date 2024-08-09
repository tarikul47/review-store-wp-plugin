<?php
namespace Tarikul\ReviewStore\Inc\Database;

use Tarikul\ReviewStore\Inc\Helper\Helper;

class Database
{
    private static $instance = null;
    private $wpdb;

    private function __construct($wpdb)
    {
        // private constructor to prevent direct instantiation
        $this->wpdb = $wpdb;
    }

    public static function getInstance($wpdb = null)
    {
        if (self::$instance === null) {
            if ($wpdb === null) {
                global $wpdb;
            }
            self::$instance = new self($wpdb);
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

        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            "external_profile" => "CREATE TABLE {$wpdb->prefix}external_profile (
                external_profile_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
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

            "reviews" => "CREATE TABLE {$wpdb->prefix}reviews (
                review_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                external_profile_id BIGINT(20) UNSIGNED NOT NULL,
                reviewer_user_id BIGINT(20) UNSIGNED NOT NULL,
                rating DECIMAL(3,2),
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (external_profile_id),
                INDEX (reviewer_user_id)
            ) $charset_collate;",

            "review_meta" => "CREATE TABLE {$wpdb->prefix}review_meta (
                meta_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                review_id BIGINT(20) UNSIGNED NOT NULL,
                meta_key VARCHAR(255),
                meta_value TEXT,
                INDEX (review_id),
                INDEX (meta_key)
            ) $charset_collate;",

            "email_queue" => "CREATE TABLE {$wpdb->prefix}email_queue (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                to_email VARCHAR(255) NOT NULL,
                subject VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            "notifications" => "CREATE TABLE {$wpdb->prefix}notifications (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                message TEXT NOT NULL,
                status ENUM('unread', 'read') DEFAULT 'unread',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (user_id)
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
     * Insert data into a table.
     *
     * @param array $user_data
     * @return int|false
     */
    public function insert(string $user_data)
    {
        $table = $this->wpdb->prefix . 'external_profile';
        $result = $this->wpdb->insert($table, $user_data);
        if ($result === false) {
            return false;
        }
        return $this->wpdb->insert_id;
    }

    /**
     * Final 
     * Insert a new user into the custom external_profile table.
     *
     * @param array $user_data The associative array containing user data.
     * @param int $product_id The ID of the product associated with the user.
     * @return int The ID of the newly inserted user.
     */
    public function insert_user($user_data, $product_id)
    {
        $this->wpdb->insert(
            "{$this->wpdb->prefix}external_profile",
            array(
                'first_name' => $user_data['first_name'],
                'last_name' => $user_data['last_name'],
                'email' => $user_data['email'],
                'phone' => $user_data['phone'],
                'address' => $user_data['address'],
                'zip_code' => $user_data['zip_code'],
                'city' => $user_data['city'],
                'salary_per_month' => $user_data['salary_per_month'], // Corrected field name
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

        return $this->wpdb->insert_id;
    }


    /**
     * Insert a new review into the reviews table.
     *
     * @param int $external_profile_id The ID of the external profile being reviewed.
     * @param int $reviewer_user_id The ID of the user who is reviewing.
     * @param float $rating The rating given by the reviewer.
     * @param string $status The status of the review ('pending', 'approved', 'rejected').
     * @return int The ID of the newly inserted review.
     */
    public function insert_review($external_profile_id, $average_rating, $status = 'pending')
    {
        // TODO: current user id = $reviewer_user_id
        // TODO: current user if admin then status will be approve 
        $user_info = Helper::get_current_user_id_and_roles();

        if ($user_info) {
            //    echo 'User ID: ' . $user_info['user_id'] . '<br>';
            //    echo 'User Roles: ' . implode(', ', $user_info['roles']);
            $reviewer_user_id = $user_info['user_id'];
            $status = in_array('administrator', $user_info['roles']) ?? 'approved';
        } else {
            die('Cheating');
        }


        $this->wpdb->insert(
            "{$this->wpdb->prefix}reviews",
            array(
                'external_profile_id' => $external_profile_id,
                'reviewer_user_id' => $reviewer_user_id,
                'rating' => $average_rating,
                'status' => $status,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ),
            array(
                '%d', // external_profile_id
                '%d', // reviewer_user_id
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
            "{$this->wpdb->prefix}review_meta",
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
     * Get users along with their total, approved, and pending review counts.
     *
     * @return array|object|null
     */
    public function get_users_with_review_data()
    {

        $query = "
            SELECT u.external_profile_id, u.name, u.email,
                   COUNT(r.review_id) as total_reviews,
                   SUM(CASE WHEN r.status = 'approved' THEN 1 ELSE 0 END) as approved_reviews,
                   SUM(CASE WHEN r.status = 'pending' THEN 1 ELSE 0 END) as pending_reviews
            FROM {$this->wpdb->prefix}external_profile u
            LEFT JOIN {$this->wpdb->prefix}reviews r ON u.external_profile_id = r.external_profile_id
            GROUP BY u.id, u.name, u.email
        ";

        return $this->wpdb->get_results($query);
    }
}
