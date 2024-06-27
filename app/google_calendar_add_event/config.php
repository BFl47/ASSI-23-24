<?php
    // Google API configuration 
    define('GOOGLE_CLIENT_ID', '938880355848-j14uru4ogt4560telt71g5h937ih89i4.apps.googleusercontent.com'); 
    define('GOOGLE_CLIENT_SECRET', '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'); 
    define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar'); 
    define('REDIRECT_URI', 'http://localhost:3000/app/google_calendar_add_event/google_calendar_event_sync.php'); 
    
    // Start session 
    if(!session_id()) session_start(); 
    
    // Google OAuth URL 
    $googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online'; 
?>