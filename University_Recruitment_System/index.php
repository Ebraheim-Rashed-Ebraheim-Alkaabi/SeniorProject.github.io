<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Internal Admin Portal - UoS Recruitment System</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { 
      background: linear-gradient(135deg, #0f1724 0%, #071026 100%); 
      color: #e6eef6; 
      min-height: 100vh; 
      padding: 2rem; 
    }
    .container { max-width: 1200px; margin: 0 auto; }
    header {
      text-align: center;
      margin-bottom: 3rem;
      padding: 2rem 0;
    }
    h1 { 
      font-size: 2.5rem; 
      margin-bottom: 0.5rem; 
      background: linear-gradient(135deg, #06b6d4, #60a5fa);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .subtitle { 
      font-size: 1.2rem; 
      color: #94a3b8; 
      margin-bottom: 1rem;
    }
    .back-link {
      display: inline-block;
      color: #06b6d4;
      text-decoration: none;
      margin-top: 1rem;
      padding: 0.5rem 1rem;
      border: 1px solid #06b6d4;
      border-radius: 6px;
      transition: all 0.3s;
    }
    .back-link:hover {
      background: #06b6d4;
      color: #022;
    }
    .modules { 
      display: grid; 
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
      gap: 2rem; 
      margin-bottom: 2rem;
    }
    .module-card { 
      background: linear-gradient(180deg, #0b1220, rgba(255,255,255,0.02)); 
      padding: 2rem; 
      border-radius: 12px; 
      border: 1px solid rgba(255,255,255,0.1);
      box-shadow: 0 6px 18px rgba(2,6,23,0.6);
      transition: all 0.3s; 
    }
    .module-card:hover { 
      transform: translateY(-5px); 
      border-color: #06b6d4;
      box-shadow: 0 10px 30px rgba(6,182,212,0.3); 
    }
    .module-card h2 { 
      font-size: 1.5rem; 
      margin-bottom: 1rem; 
      color: #06b6d4; 
    }
    .module-card p { 
      margin-bottom: 1.5rem; 
      color: #94a3b8; 
      line-height: 1.6; 
    }
    .module-links { 
      display: flex; 
      flex-direction: column; 
      gap: 0.8rem; 
    }
    .module-links a { 
      color: #e6eef6; 
      text-decoration: none; 
      padding: 0.8rem 1.2rem; 
      background: rgba(255,255,255,0.05); 
      border-radius: 8px; 
      transition: all 0.3s; 
      border: 1px solid rgba(255,255,255,0.1); 
    }
    .module-links a:hover { 
      background: rgba(6,182,212,0.2); 
      border-color: #06b6d4;
      transform: translateX(5px); 
    }
    .info-box {
      background: rgba(6,182,212,0.1);
      border: 1px solid #06b6d4;
      border-radius: 8px;
      padding: 1.5rem;
      margin-top: 2rem;
    }
    .info-box h3 {
      color: #06b6d4;
      margin-bottom: 0.5rem;
    }
    .info-box p {
      color: #94a3b8;
      line-height: 1.6;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>🏛️ Internal Admin Portal</h1>
      <p class="subtitle">University of Sharjah Faculty Recruitment System</p>
      <a href="../index.html" class="back-link">← Back to Main Portal</a>
    </header>

    <div class="modules">
      <!-- Recruitment Management -->
      <div class="module-card">
        <h2>🔐 Admin Login</h2>
        <p>Login required to access admin features. Use your HR, HoD, Dean, or Committee credentials.</p>
        <div class="module-links">
          <a href="login.html" style="background: #06b6d4; color: #022; font-weight: 700;">🔐 Login to Admin Portal</a>
        </div>
      </div>

      <div class="module-card">
        <h2>📝 Talent Request Submission</h2>
        <p>Head of Department (HoD) can submit requests for new faculty positions with job descriptions and requirements.</p>
        <div class="module-links">
          <a href="talent_request_submission_form.html">📝 Submit Talent Request</a>
        </div>
      </div>

      <div class="module-card">
        <h2>📈 Recruitment Dashboard</h2>
        <p>HR view of all applications, pipeline visualization, and recruitment statistics across all open positions.</p>
        <div class="module-links">
          <a href="recruitment_dashboard_hr_view.html">📊 View Dashboard</a>
        </div>
      </div>

      <div class="module-card">
        <h2>🔍 Application Review</h2>
        <p>Review and assess applications with technical, behavioral, and values alignment evaluations. Recommend or reject candidates.</p>
        <div class="module-links">
          <a href="application_review_interface.html">🔍 Review Applications</a>
        </div>
      </div>

      <div class="module-card">
        <h2>📅 Interview Scheduling</h2>
        <p>Schedule interviews, manage panel members, and collect interview feedback with aggregated recommendations.</p>
        <div class="module-links">
          <a href="interview_scheduling_feedback.html">📅 Schedule Interviews</a>
        </div>
      </div>

      <!-- Onboarding -->
      <div class="module-card">
        <h2>✅ Pre-Joining Checklist</h2>
        <p>New faculty members complete required tasks, upload documents, and track their onboarding progress.</p>
        <div class="module-links">
          <a href="pre_joining_checklist.html">✅ View Checklist</a>
        </div>
      </div>

      <div class="module-card">
        <h2>👨‍💼 HR Onboarding Management</h2>
        <p>HR tracks all new hires, assigns tasks, monitors progress, and manages onboarding workflow.</p>
        <div class="module-links">
          <a href="hr_onboarding_management.html">👨‍💼 Manage Onboarding</a>
        </div>
      </div>

      <div class="module-card">
        <h2>👋 New Faculty Welcome</h2>
        <p>Welcome page for new faculty with countdown timer, resources, and onboarding journey information.</p>
        <div class="module-links">
          <a href="new_faculty_welcome.html">👋 Welcome Page</a>
        </div>
      </div>
    </div>

    <div class="info-box">
      <h3>ℹ️ Access Information</h3>
      <p>
        <strong>Admin Login Required:</strong><br>
        Please <a href="login.html" style="color: #06b6d4; text-decoration: underline;">login here</a> to access admin features.<br><br>
        <strong>Demo Credentials:</strong><br>
        Email: <code>admin@sharjah.ac.ae</code><br>
        Password: <code>password</code><br><br>
        <strong>Note:</strong> Some features require backend API integration. Make sure the database is set up and backend is running.
      </p>
    </div>
  </div>
</body>
</html>
