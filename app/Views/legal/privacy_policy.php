<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - CHAKANOKS</title>
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
            content: "🔒";
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
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-left: 4px solid #2d5016;
            padding: 20px 25px;
            border-radius: 0 12px 12px 0;
            margin: 25px 0;
        }
        
        .highlight-box p {
            margin: 0;
            color: #1b5e20;
        }
        
        .warning-box {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-left: 4px solid #f59e0b;
            padding: 20px 25px;
            border-radius: 0 12px 12px 0;
            margin: 25px 0;
        }
        
        .warning-box p {
            margin: 0;
            color: #e65100;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .data-table th {
            background: #2d5016;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .data-table tr:nth-child(even) {
            background: #f9f9f9;
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
            
            .data-table {
                font-size: 0.85rem;
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
            <h1>Privacy Policy</h1>
            <p class="last-updated">Last Updated: December 1, 2024</p>
            
            <p>CHAKANOKS ("we," "our," or "us") is committed to protecting the privacy and security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our Supply Chain Management System.</p>
            
            <div class="highlight-box">
                <p><strong>🛡️ Your Privacy Matters:</strong> At CHAKANOKS, we treat your data with the same care we put into preparing our signature chicken dishes – with attention to quality, safety, and trust.</p>
            </div>
            
            <h2>1. Information We Collect</h2>
            <p>We collect information necessary for the operation of our supply chain management system:</p>
            
            <table class="data-table">
                <tr>
                    <th>Data Type</th>
                    <th>Purpose</th>
                </tr>
                <tr>
                    <td><strong>Account Information</strong><br>Name, email, employee ID, role</td>
                    <td>User authentication and access control</td>
                </tr>
                <tr>
                    <td><strong>Work Information</strong><br>Branch assignment, department, position</td>
                    <td>System permissions and workflow routing</td>
                </tr>
                <tr>
                    <td><strong>Activity Logs</strong><br>Login times, actions performed, changes made</td>
                    <td>Security auditing and accountability</td>
                </tr>
                <tr>
                    <td><strong>Device Information</strong><br>IP address, browser type, device type</td>
                    <td>Security monitoring and troubleshooting</td>
                </tr>
            </table>
            
            <h2>2. How We Use Your Information</h2>
            <p>Your information is used exclusively for legitimate business purposes:</p>
            <ul>
                <li><strong>System Access:</strong> Authenticate your identity and provide appropriate access levels</li>
                <li><strong>Operations:</strong> Process inventory transactions, purchase requests, and deliveries</li>
                <li><strong>Communication:</strong> Send system notifications about orders, deliveries, and stock alerts</li>
                <li><strong>Security:</strong> Monitor for unauthorized access and suspicious activities</li>
                <li><strong>Compliance:</strong> Maintain records required by food safety and business regulations</li>
                <li><strong>Improvement:</strong> Analyze usage patterns to enhance system performance</li>
            </ul>
            
            <h2>3. Information Sharing</h2>
            <p>We do not sell your personal information. We may share information only in these circumstances:</p>
            <ul>
                <li><strong>Within CHAKANOKS:</strong> Between branches and departments as needed for operations</li>
                <li><strong>With Suppliers:</strong> Order and delivery information necessary for fulfillment</li>
                <li><strong>Legal Requirements:</strong> When required by law, court order, or regulatory authority</li>
                <li><strong>Business Transfers:</strong> In the event of merger, acquisition, or sale of assets</li>
            </ul>
            
            <div class="warning-box">
                <p><strong>⚠️ Important:</strong> We never share your personal login credentials with suppliers or third parties. Only business transaction data is shared as necessary.</p>
            </div>
            
            <h2>4. Data Security</h2>
            <p>We implement robust security measures to protect your information:</p>
            <ul>
                <li>Encrypted data transmission using HTTPS/SSL protocols</li>
                <li>Secure password hashing and storage</li>
                <li>Role-based access controls limiting data visibility</li>
                <li>Regular security audits and vulnerability assessments</li>
                <li>Automatic session timeouts for inactive users</li>
                <li>Comprehensive activity logging and monitoring</li>
            </ul>
            
            <h2>5. Data Retention</h2>
            <p>We retain your information for the following periods:</p>
            <ul>
                <li><strong>Active Accounts:</strong> For the duration of your employment or business relationship</li>
                <li><strong>Transaction Records:</strong> 7 years for financial and regulatory compliance</li>
                <li><strong>Activity Logs:</strong> 2 years for security and auditing purposes</li>
                <li><strong>Terminated Accounts:</strong> 90 days after account deactivation, then anonymized</li>
            </ul>
            
            <h2>6. Your Rights</h2>
            <p>As a user of our system, you have the right to:</p>
            <ul>
                <li><strong>Access:</strong> Request a copy of your personal data we hold</li>
                <li><strong>Correction:</strong> Request correction of inaccurate personal information</li>
                <li><strong>Deletion:</strong> Request deletion of your data (subject to legal retention requirements)</li>
                <li><strong>Objection:</strong> Object to certain types of data processing</li>
                <li><strong>Portability:</strong> Receive your data in a portable format</li>
            </ul>
            <p>To exercise these rights, contact your supervisor or our Privacy Officer.</p>
            
            <h2>7. Cookies and Tracking</h2>
            <p>Our system uses essential cookies for:</p>
            <ul>
                <li>Maintaining your login session</li>
                <li>Remembering your preferences (e.g., selected branch)</li>
                <li>Security tokens for form submissions</li>
            </ul>
            <p>We do not use advertising or third-party tracking cookies.</p>
            
            <h2>8. Third-Party Services</h2>
            <p>Our system may integrate with:</p>
            <ul>
                <li><strong>Email Services:</strong> For sending notifications and alerts</li>
                <li><strong>Cloud Hosting:</strong> Secure servers for data storage</li>
            </ul>
            <p>All third-party providers are contractually bound to protect your data.</p>
            
            <h2>9. Children's Privacy</h2>
            <p>Our Supply Chain Management System is intended for use by authorized employees and business partners only. We do not knowingly collect information from individuals under 18 years of age.</p>
            
            <h2>10. Changes to This Policy</h2>
            <p>We may update this Privacy Policy periodically. Significant changes will be communicated through system notifications. Continued use of the system after changes constitutes acceptance of the updated policy.</p>
            
            <h2>11. Philippine Data Privacy Act Compliance</h2>
            <p>CHAKANOKS complies with Republic Act No. 10173, also known as the Data Privacy Act of 2012. We are committed to:</p>
            <ul>
                <li>Transparency in data collection and processing</li>
                <li>Legitimate purpose for all data processing activities</li>
                <li>Proportionality in data collection (only what is necessary)</li>
                <li>Respecting your rights as a data subject</li>
            </ul>
            
            <div class="contact-section">
                <h2>12. Contact Us</h2>
                <p>For privacy concerns or to exercise your data rights, contact our Privacy Officer:</p>
                <p><strong>CHAKANOKS Data Privacy Office</strong><br>
                Email: <a href="mailto:privacy@chakanoks.com">privacy@chakanoks.com</a><br>
                Phone: (02) 8888-CHKN (2456)<br>
                Address: CHAKANOKS Head Office, Metro Manila, Philippines</p>
                <p style="margin-top: 15px; font-size: 0.9rem; opacity: 0.8;">For urgent security concerns, please call our hotline immediately.</p>
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

