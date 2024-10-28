jQuery(document).ready(function ($) {
  // Replace the textarea with TinyMCE
  var editorID = "woocommerce_wc_tjmk_email_custom_rich_message";
  wp.editor.initialize(editorID, {
    tinymce: {
      wpautop: true,
      plugins: "lists,paste,wordpress",
      toolbar1: "bold,italic,underline,bullist,numlist,link,unlink",
      toolbar2: "",
    },
    quicktags: true,
  });
});
