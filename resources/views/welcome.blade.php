<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Love NGO - Proposal Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica Neue', sans-serif;
        }

        body {
            background: #f8fafc;
        }

        nav {
            background: #ffffff;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: fadeInDown 0.8s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #2563eb, #3b82f6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .brand-tagline {
            font-size: 0.9rem;
            color: #64748b;
            border-left: 2px solid #e2e8f0;
            padding-left: 1rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #334155;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1.5rem;
            }
            .brand-tagline {
                display: none;
            }
        }
    </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar">
    <div class="logo">OneLove</div>
    <ul class="nav-links">
      <li><a href="/login" class="nav-btn">Login</a></li>
      <li><a href="/register" class="nav-btn register">Register</a></li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-text">
      <h1>Transform Your NGO's Impact</h1>
      <p>Streamline your proposal process with our collaborative platform tailored for nonprofits.</p>
      <a href="/register" class="cta">Get Started</a>
    </div>
    <div class="hero-img">
      <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1350&q=80" alt="Team collaboration">
    </div>
  </section>

  <!-- Feature Cards -->
  <section class="features">
    <div class="feature-card">
      <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=1350&q=80" alt="Proposal drafting">
      <h3>Smart Proposal Builder</h3>
      <p>AI-assisted template system with real-time formatting.</p>
    </div>
    <div class="feature-card">
      <img src="https://images.unsplash.com/photo-1573164574572-cb89e39749b4?auto=format&fit=crop&w=1350&q=80" alt="Analytics dashboard">
      <h3>Impact Analytics</h3>
      <p>Track success rates and generate funding reports.</p>
    </div>
    <div class="feature-card">
      <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1350&q=80" alt="Team collaboration">
      <h3>Team Collaboration</h3>
      <p>Version control and comment threading for seamless teamwork.</p>
    </div>
  </section>

  <!-- Demo Showcase -->
  <section class="demo">
    <h2>See It in Action</h2>
    <div class="demo-container">
      <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1350&q=80" alt="Platform interface" class="demo-img">
      <div class="demo-tags">
        <span>Real-time Editing</span>
        <span>Version History</span>
        <span>Feedback System</span>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <p>© 2025 OneLove NGO Platform. All rights reserved.</p>
      <div class="social-links">
        <a href="#" title="Facebook"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"></a>
        <a href="#" title="Twitter"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter"></a>
        <a href="#" title="LinkedIn"><img src="https://cdn-icons-png.flaticon.com/512/145/145807.png" alt="LinkedIn"></a>
        <a href="mailto:contact@onelove.org" title="Email"><img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" alt="Email"></a>
      </div>
    </div>
  </footer>

  <!-- Styles -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f9fafb;
      color: #1f2937;
    }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 2rem;
      background: #ffffff;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    }

    .logo {
      font-size: 1.75rem;
      font-weight: bold;
      color: #1f2937;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1rem;
    }

    .nav-links li a {
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-weight: 600;
      transition: 0.3s;
      color: #1f2937;
    }

    .nav-btn:hover {
      background: #f3f4f6;
    }

    .register {
      background: #f97316;
      color: white;
    }

    .register:hover {
      background: #ea580c;
    }

    /* Hero Section */
    .hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      align-items: center;
      padding: 4rem 2rem;
      max-width: 1200px;
      margin: auto;
    }

    .hero-text h1 {
      font-size: 2.75rem;
      margin-bottom: 1rem;
    }

    .hero-text p {
      font-size: 1.1rem;
      color: #4b5563;
      margin-bottom: 2rem;
    }

    .cta {
      padding: 0.75rem 1.5rem;
      background: #f97316;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: background 0.3s;
    }

    .cta:hover {
      background: #ea580c;
    }

    .hero-img img {
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    /* Features Section */
    .features {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      max-width: 1200px;
      margin: 4rem auto;
      padding: 0 2rem;
    }

    .feature-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      transition: transform 0.3s;
    }

    .feature-card:hover {
      transform: translateY(-6px);
    }

    .feature-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .feature-card h3 {
      margin: 1rem 1.25rem 0.5rem;
      font-size: 1.25rem;
    }

    .feature-card p {
      margin: 0 1.25rem 1.5rem;
      color: #6b7280;
    }

    /* Demo Section */
    .demo {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 0 2rem;
      text-align: center;
    }

    .demo h2 {
      font-size: 2rem;
      margin-bottom: 2rem;
    }

    .demo-container {
      position: relative;
    }

    .demo-img {
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    }

    .demo-tags {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 2rem;
    }

    .demo-tags span {
      background: #fef3c7;
      color: #92400e;
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      font-weight: 600;
    }

    /* Footer */
    .footer {
      background: #1f2937;
      color: #f3f4f6;
      padding: 2rem;
      text-align: center;
    }

    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }

    .social-links a img {
      width: 28px;
      margin: 0 10px;
      opacity: 0.8;
      transition: opacity 0.3s;
    }

    .social-links a:hover img {
      opacity: 1;
    }

    @media (max-width: 768px) {
      .hero {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .features {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>

</html>