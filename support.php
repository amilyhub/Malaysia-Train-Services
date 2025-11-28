<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - Malaysia Train Services</title>
    <style>
        :root {
            --pastel-pink: #ffd6e7;
            --pastel-green: #d6f5e3;
            --pink-accent: #ff85a2;
            --green-accent: #7ecf9b;
            --text-dark: #4a4a4a;
            --text-light: #888888;
            --border-light: #e8d4e0;
            --white: #ffffff;
            --background: #fef7fa;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-dark);
        }

        .support-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px 35px;
            width: 100%;
            max-width: 1000px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .support-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .company-header h1 {
            color: var(--text-dark);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .company-header .tagline {
            color: var(--text-light);
            font-size: 18px;
            font-weight: 400;
        }

        .navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .support-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .contact-info {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .contact-info h2 {
            color: var(--text-dark);
            margin-bottom: 25px;
            font-size: 24px;
            text-align: center;
        }

        .contact-method {
            background: var(--white);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid var(--pink-accent);
            transition: transform 0.3s ease;
        }

        .contact-method:hover {
            transform: translateX(5px);
        }

        .contact-method h3 {
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 16px;
        }

        .contact-method p {
            color: var(--text-light);
            font-size: 14px;
        }

        .support-form {
            padding: 30px;
            background: var(--white);
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .support-form h2 {
            color: var(--text-dark);
            margin-bottom: 25px;
            font-size: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 14px;
            padding-left: 8px;
            border-left: 3px solid var(--pink-accent);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--pastel-pink);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: var(--white);
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--pink-accent);
            box-shadow: 0 0 0 4px rgba(255, 133, 162, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .faq-section {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            border: 1px solid var(--border-light);
        }

        .faq-section h2 {
            color: var(--text-dark);
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
        }

        .faq-item {
            background: var(--white);
            margin-bottom: 15px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-light);
        }

        .faq-question {
            padding: 20px;
            background: var(--white);
            color: var(--text-dark);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background: var(--pastel-pink);
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .faq-answer.active {
            padding: 20px;
            max-height: 500px;
        }

        .faq-toggle {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-toggle {
            transform: rotate(45deg);
        }

        .emergency-section {
            background: linear-gradient(135deg, #ff6b8b 0%, #ff8e53 100%);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            color: var(--white);
            margin-bottom: 30px;
        }

        .emergency-section h2 {
            margin-bottom: 15px;
            font-size: 24px;
        }

        .emergency-number {
            font-size: 32px;
            font-weight: 700;
            margin: 15px 0;
        }

        .emergency-note {
            font-size: 14px;
            opacity: 0.9;
        }

        .footer {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid var(--pastel-pink);
            color: var(--text-light);
            font-size: 14px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .footer-links a {
            color: var(--pink-accent);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--green-accent);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .support-container {
                padding: 30px 20px;
            }
            
            .support-content {
                grid-template-columns: 1fr;
            }
            
            .navigation {
                gap: 10px;
            }
            
            .nav-btn {
                padding: 10px 15px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="support-container">
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">We're Here to Help You</p>
        </div>

        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
            <a href="trainschedule.php" class="nav-btn">Train Schedule</a>
            <a href="login.php" class="nav-btn">Login</a>
            <a href="register.php" class="nav-btn">Register</a>
            <a href="about.php" class="nav-btn">About</a>
            <a href="support.php" class="nav-btn">Support</a>
        </div>

        <div class="emergency-section">
            <h2>🚨 Emergency Assistance</h2>
            <div class="emergency-number">1-800-88-1234</div>
            <p class="emergency-note">Available 24/7 for urgent train-related emergencies</p>
        </div>

        <div class="support-content">
            <div class="contact-info">
                <h2>Contact Information</h2>
                
                <div class="contact-method">
                    <h3>📞 Customer Service Hotline</h3>
                    <p>1-300-88-1234</p>
                    <p style="font-size: 12px; color: var(--text-light);">Monday - Sunday, 7:00 AM - 11:00 PM</p>
                </div>
                
                <div class="contact-method">
                    <h3>📧 Email Support</h3>
                    <p>support@malaysiatrains.gov.my</p>
                    <p style="font-size: 12px; color: var(--text-light);">Response within 24 hours</p>
                </div>
                
                <div class="contact-method">
                    <h3>💬 Live Chat</h3>
                    <p>Available on website</p>
                    <p style="font-size: 12px; color: var(--text-light);">Monday - Friday, 9:00 AM - 6:00 PM</p>
                </div>
                
                <div class="contact-method">
                    <h3>🏢 Headquarters</h3>
                    <p>Malaysia Train Services<br>
                       Level 12, Menara MTS<br>
                       Jalan Tun Razak<br>
                       50400 Kuala Lumpur</p>
                </div>
            </div>

            <div class="support-form">
                <h2>Send Us a Message</h2>
                <form id="supportForm">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select class="form-control" id="subject" required>
                            <option value="">Select inquiry type</option>
                            <option value="booking">Booking Issue</option>
                            <option value="refund">Refund Request</option>
                            <option value="schedule">Schedule Inquiry</option>
                            <option value="technical">Technical Problem</option>
                            <option value="complaint">Complaint</option>
                            <option value="suggestion">Suggestion</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" placeholder="Please describe your issue in detail..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>
        </div>

        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    How can I change my booking?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    You can change your booking up to 2 hours before departure through our website or mobile app. Login to your account, go to "My Bookings", and select "Modify Booking". Changes may be subject to fare differences.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    What is your refund policy?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Refunds are available for cancellations made at least 24 hours before departure. Refunds will be processed within 7-10 business days to your original payment method. Service fees may apply.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    Do you offer student discounts?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Yes! We offer 15% discount for students with valid student ID. Simply present your student ID during booking and at the station. This discount applies to all economy class tickets.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    How early should I arrive at the station?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    We recommend arriving at least 30 minutes before departure for domestic routes and 45 minutes for international routes. This allows time for security checks and boarding procedures.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    Can I bring luggage on the train?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Yes, each passenger can bring up to 2 pieces of luggage with a maximum weight of 20kg each. Additional luggage may be subject to extra fees. Certain items are prohibited for safety reasons.
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2024 Malaysia Train Services. All rights reserved.</p>
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="trainschedule.php">Schedule</a>
                <a href="about.php">About Us</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const answer = this.nextElementSibling;
                
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== faqItem) {
                        item.classList.remove('active');
                        item.querySelector('.faq-answer').classList.remove('active');
                    }
                });
                
                faqItem.classList.toggle('active');
                answer.classList.toggle('active');
            });
        });

        document.getElementById('supportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            
            const submitBtn = this.querySelector('.btn-submit');
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                alert(`Thank you ${name}! Your message has been received. We'll respond to ${email} within 24 hours.`);
                this.reset();
                submitBtn.textContent = 'Send Message';
                submitBtn.disabled = false;
            }, 2000);
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });

        document.querySelector('.emergency-number').addEventListener('click', function() {
            if (confirm('Call emergency hotline: 1-800-88-1234?')) {
                window.location.href = 'tel:1-800-88-1234';
            }
        });
    </script>
</body>
</html>