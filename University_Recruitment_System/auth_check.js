/**
 * Authentication Check Script
 * Add this to admin pages to require login
 * 
 * Usage: Add this before closing </body> tag:
 * <script src="auth_check.js"></script>
 */

(function() {
    // Check if user is logged in
    function checkAuth() {
        const loggedIn = localStorage.getItem('admin_logged_in');
        const user = localStorage.getItem('admin_user');
        
        if (!loggedIn || !user) {
            // Not logged in, redirect to login
            window.location.href = 'login.html';
            return false;
        }
        
        return true;
    }

    // Logout function
    function logout() {
        localStorage.removeItem('admin_logged_in');
        localStorage.removeItem('admin_user');
        window.location.href = 'login.html';
    }

    // Make logout available globally
    window.adminLogout = logout;

    // Check auth on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkAuth);
    } else {
        checkAuth();
    }
})();

