<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - CHAKANOKS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f0e8 0%, #fff9f0 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.7;
        }
        
        .header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .header-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: #f59e0b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .logo-subtitle {
            font-size: 0.7rem;
            opacity: 0.9;
            letter-spacing: 2px;
        }
        
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        
        h1 {
            color: #2d5016;
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        h1::before {
            content: "📜";
            font-size: 2rem;
        }
        
        .last-updated {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        h2 {
            color: #2d5016;
            font-size: 1.4rem;
            margin: 35px 0 15px;
            padding-left: 15px;
            border-left: 4px solid #f59e0b;
        }
        
        p {
            margin-bottom: 15px;
            color: #555;
        }
        
        ul {
            margin: 15px 0 20px 25px;
            color: #555;
        }
        
        li {
            margin-bottom: 10px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%);
            border-left: 4px solid #f59e0b;
            padding: 20px 25px;
            border-radius: 0 12px 12px 0;
            margin: 25px 0;
        }
        
        .highlight-box p {
            margin: 0;
            color: #6b5a00;
        }
        
        .contact-section {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-top: 40px;
        }
        
        .contact-section h2 {
            color: white;
            border-left-color: #f59e0b;
            margin-top: 0;
        }
        
        .contact-section p {
            color: rgba(255,255,255,0.9);
        }
        
        .contact-section a {
            color: #f59e0b;
        }
        
        .footer {
            text-align: center;
            padding: 30px;
            color: #888;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .content-card {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <a href="<?= base_url('/') ?>" class="logo">
                <div class="logo-icon">🍗</div>
                <div>
                    <div class="logo-text">CHAKANOKS</div>
                    <div class="logo-subtitle">Supply Chain Management</div>
                </div>
            </a>
            <a href="<?= base_url('/') ?>" class="back-btn">← Back to Login</a>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <h1>Terms of Service</h1>
            <p class="last-updated">Last Updated: December 1, 2024</p>
            
            <p>Welcome to CHAKANOKS Supply Chain Management System. These Terms of Service ("Terms") govern your access to and use of our supply chain management platform and related services. By accessing or using our system, you agree to be bound by these Terms.</p>
            
            <div class="highlight-box">
                <p><strong>🍗 CHAKANOKS</strong> is a premier chicken restaurant committed to delivering quality poultry products through efficient supply chain operations. Our management system ensures freshness from farm to table.</p>
            </div>
            
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing the CHAKANOKS Supply Chain Management System, you acknowledge that you have read, understood, and agree to be bound by these Terms. If you do not agree to these Terms, you must not access or use our system.</p>
            
            <h2>2. User Accounts</h2>
            <p>To access our system, you must be an authorized employee, manager, or partner of CHAKANOKS. You agree to:</p>
            <ul>
                <li>Maintain the confidentiality of your login credentials</li>
                <li>Immediately notify management of any unauthorized use of your account</li>
                <li>Accept responsibility for all activities that occur under your account</li>
                <li>Not share your account access with unauthorized individuals</li>
            </ul>
            
            <h2>3. Authorized Use</h2>
            <p>The CHAKANOKS Supply Chain Management System is provided exclusively for:</p>
            <ul>
                <li>Managing inventory of chicken products and restaurant supplies</li>
                <li>Processing purchase requests and orders from approved suppliers</li>
                <li>Tracking deliveries and stock movements across branches</li>
                <li>Generating reports for operational and management purposes</li>
                <li>Coordinating logistics between branches and suppliers</li>
            </ul>
            
            <h2>4. Prohibited Activities</h2>
            <p>Users are strictly prohibited from:</p>
            <ul>
                <li>Attempting to gain unauthorized access to any part of the system</li>
                <li>Manipulating inventory records or financial data fraudulently</li>
                <li>Sharing confidential supplier or pricing information externally</li>
                <li>Using the system for any purpose other than authorized business operations</li>
                <li>Introducing malware, viruses, or harmful code into the system</li>
            </ul>
            
            <h2>5. Data Accuracy</h2>
            <p>Users are responsible for ensuring the accuracy of data entered into the system, including:</p>
            <ul>
                <li>Inventory counts and stock levels</li>
                <li>Product expiration dates and quality assessments</li>
                <li>Delivery confirmations and receipt records</li>
                <li>Purchase request details and specifications</li>
            </ul>
            
            <h2>6. Food Safety Compliance</h2>
            <p>As a chicken restaurant supply chain system, all users must adhere to food safety regulations:</p>
            <ul>
                <li>Accurately report and manage product expiration dates</li>
                <li>Properly document any damaged or spoiled goods</li>
                <li>Follow cold chain management protocols for poultry products</li>
                <li>Report any food safety concerns immediately to management</li>
            </ul>
            
            <h2>7. Intellectual Property</h2>
            <p>All content, features, and functionality of the CHAKANOKS Supply Chain Management System, including but not limited to text, graphics, logos, and software, are the exclusive property of CHAKANOKS and are protected by copyright and other intellectual property laws.</p>
            
            <h2>8. System Availability</h2>
            <p>While we strive to maintain system availability, CHAKANOKS does not guarantee uninterrupted access. We may perform maintenance, updates, or experience technical issues that temporarily affect system access.</p>
            
            <h2>9. Limitation of Liability</h2>
            <p>CHAKANOKS shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the system, including but not limited to loss of data, business interruption, or operational delays.</p>
            
            <h2>10. Termination</h2>
            <p>CHAKANOKS reserves the right to terminate or suspend your access to the system at any time, with or without cause, including but not limited to violation of these Terms or termination of employment.</p>
            
            <h2>11. Changes to Terms</h2>
            <p>CHAKANOKS reserves the right to modify these Terms at any time. Users will be notified of significant changes, and continued use of the system constitutes acceptance of the modified Terms.</p>
            
            <div class="contact-section">
                <h2>12. Contact Information</h2>
                <p>For questions about these Terms of Service, please contact:</p>
                <p><strong>CHAKANOKS Restaurant Management</strong><br>
                Email: <a href="mailto:legal@chakanoks.com">legal@chakanoks.com</a><br>
                Phone: (02) 8888-CHKN (2456)</p>
            </div>
        </div>
        
        <div class="footer">
            <p>© <?= date('Y') ?> CHAKANOKS Restaurant. All rights reserved.</p>
            <p style="margin-top: 10px;">
                <a href="<?= base_url('legal/terms') ?>" style="color: #2d5016;">Terms of Service</a> · 
                <a href="<?= base_url('legal/privacy') ?>" style="color: #2d5016;">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>
</html>

