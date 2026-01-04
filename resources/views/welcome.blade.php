<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome | School ERP System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
      color: #333;
      overflow-x: hidden;
    }
    
    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/><circle cx="900" cy="800" r="80" fill="url(%23a)"/></svg>') no-repeat center center;
      background-size: cover;
      opacity: 0.3;
    }
    
    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      color: white;
    }
    
    .hero h1 {
      font-size: 4rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      text-shadow: 0 4px 20px rgba(0,0,0,0.3);
      animation: fadeInUp 1s ease-out;
    }
    
    .hero .subtitle {
      font-size: 1.4rem;
      font-weight: 300;
      margin-bottom: 3rem;
      opacity: 0.9;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeInUp 1s ease-out 0.2s both;
    }
    
    .btn-group {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 4rem;
      animation: fadeInUp 1s ease-out 0.4s both;
    }
    
    .btn-custom {
      padding: 15px 35px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      border: 2px solid transparent;
      font-size: 1.1rem;
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary-custom {
      background: rgba(255,255,255,0.2);
      color: white;
      border-color: rgba(255,255,255,0.3);
      backdrop-filter: blur(10px);
    }
    
    .btn-primary-custom:hover {
      background: rgba(255,255,255,0.3);
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    
    .btn-outline-custom {
      background: transparent;
      color: white;
      border-color: rgba(255,255,255,0.5);
    }
    
    .btn-outline-custom:hover {
      background: rgba(255,255,255,0.1);
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    
    /* Portal Cards Section */
    .portals {
      padding: 100px 0;
      background: #f8f9fa;
    }
    
    .section-title {
      text-align: center;
      margin-bottom: 4rem;
    }
    
    .section-title h2 {
      font-size: 3rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 1rem;
    }
    
    .section-title p {
      font-size: 1.2rem;
      color: #6c757d;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .portal-card {
      background: white;
      padding: 3rem 2rem;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      height: 100%;
      border: 1px solid #e9ecef;
      text-decoration: none;
      color: inherit;
      display: block;
    }
    
    .portal-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
      color: inherit;
      text-decoration: none;
    }
    
    .portal-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
    }
    
    .portal-card h4 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #2c3e50;
    }
    
    .portal-card p {
      color: #6c757d;
      line-height: 1.6;
    }
    
    /* Features Section */
    .features {
      padding: 100px 0;
      background: white;
    }
    
    .feature-card {
      background: #f8f9fa;
      padding: 3rem 2rem;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      height: 100%;
      border: 1px solid #e9ecef;
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
    }
    
    .feature-card h4 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #2c3e50;
    }
    
    .feature-card p {
      color: #6c757d;
      line-height: 1.6;
    }
    
    /* Stats Section */
    .stats {
      background: linear-gradient(135deg, #2c3e50, #34495e);
      padding: 80px 0;
      color: white;
    }
    
    .stat-item {
      text-align: center;
      padding: 2rem;
    }
    
    .stat-number {
      font-size: 3.5rem;
      font-weight: 800;
      color: #3498db;
      margin-bottom: 0.5rem;
    }
    
    .stat-label {
      font-size: 1.2rem;
      font-weight: 500;
      opacity: 0.9;
    }
    
    /* Testimonials */
    .testimonials {
      padding: 100px 0;
      background: #f8f9fa;
    }
    
    .testimonial-card {
      background: white;
      padding: 3rem;
      border-radius: 20px;
      text-align: center;
      margin: 2rem 0;
      position: relative;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .testimonial-card::before {
      content: '"';
      position: absolute;
      top: -20px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 4rem;
      color: #667eea;
      font-weight: bold;
    }
    
    .testimonial-text {
      font-size: 1.2rem;
      font-style: italic;
      margin-bottom: 2rem;
      color: #555;
    }
    
    .testimonial-author {
      font-weight: 600;
      color: #2c3e50;
    }
    
    /* Footer */
    .footer {
      background: #2c3e50;
      color: white;
      padding: 60px 0 30px;
      text-align: center;
    }
    
    .footer h5 {
      font-weight: 600;
      margin-bottom: 1rem;
    }
    
    .footer p {
      opacity: 0.8;
      margin-bottom: 2rem;
    }
    
    .social-links {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    
    .social-links a {
      width: 50px;
      height: 50px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .social-links a:hover {
      background: #3498db;
      transform: translateY(-3px);
    }
    
    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }
    
    .floating {
      animation: float 6s ease-in-out infinite;
    }
    
    /* Demo Video Section */
    .demo-section {
      padding: 100px 0;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .demo-video-container {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      background: #000;
    }
    
    .demo-video {
      width: 100%;
      height: 500px;
      object-fit: cover;
    }
    
    .demo-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
    }
    
    .play-button {
      width: 80px;
      height: 80px;
      background: rgba(255,255,255,0.9);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: #667eea;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .play-button:hover {
      transform: scale(1.1);
      background: white;
    }
    
    /* Feature Highlights Grid */
    .feature-highlights {
      padding: 100px 0;
      background: white;
    }
    
    .highlight-card {
      background: #f8f9fa;
      padding: 2.5rem 2rem;
      border-radius: 15px;
      text-align: center;
      transition: all 0.3s ease;
      height: 100%;
      border: 1px solid #e9ecef;
    }
    
    .highlight-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
      background: white;
    }
    
    .highlight-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 1.5rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }
    
    .highlight-card h5 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #2c3e50;
    }
    
    /* CTA Section */
    .cta-section {
      padding: 100px 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      text-align: center;
    }
    
    .cta-buttons {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 3rem;
    }
    
    .btn-cta {
      padding: 15px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      border: 2px solid transparent;
      font-size: 1.1rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .btn-cta-primary {
      background: white;
      color: #667eea;
    }
    
    .btn-cta-primary:hover {
      background: #f8f9fa;
      color: #667eea;
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .btn-cta-outline {
      background: transparent;
      color: white;
      border-color: rgba(255,255,255,0.5);
    }
    
    .btn-cta-outline:hover {
      background: rgba(255,255,255,0.1);
      color: white;
      transform: translateY(-3px);
    }
    
    /* Client Logos Section */
    .clients-section {
      padding: 80px 0;
      background: #f8f9fa;
    }
    
    .client-logo {
      height: 60px;
      width: auto;
      opacity: 0.6;
      transition: all 0.3s ease;
      filter: grayscale(100%);
    }
    
    .client-logo:hover {
      opacity: 1;
      filter: grayscale(0%);
      transform: scale(1.1);
    }
    
    /* FAQ Section */
    .faq-section {
      padding: 100px 0;
      background: white;
    }
    
    .faq-item {
      background: #f8f9fa;
      border-radius: 10px;
      margin-bottom: 1rem;
      overflow: hidden;
    }
    
    .faq-question {
      padding: 1.5rem;
      background: #e9ecef;
      border: none;
      width: 100%;
      text-align: left;
      font-weight: 600;
      color: #2c3e50;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .faq-question:hover {
      background: #dee2e6;
    }
    
    .faq-answer {
      padding: 1.5rem;
      color: #6c757d;
      line-height: 1.6;
      display: none;
    }
    
    .faq-answer.show {
      display: block;
    }
    
    .faq-icon {
      transition: transform 0.3s ease;
    }
    
    .faq-icon.rotated {
      transform: rotate(180deg);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }
      
      .hero .subtitle {
        font-size: 1.1rem;
        padding: 0 1rem;
      }
      
      .btn-group {
        flex-direction: column;
        align-items: center;
      }
      
      .btn-custom {
        width: 250px;
        margin-bottom: 1rem;
      }
      
      .section-title h2 {
        font-size: 2rem;
      }
      
      .portal-card, .feature-card {
        margin-bottom: 2rem;
      }
      
      .stat-number {
        font-size: 2.5rem;
      }
      
      .demo-video {
        height: 300px;
      }
      
      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .btn-cta {
        width: 250px;
        justify-content: center;
      }
    }

    /* Footer Text and Heart Animation */
    .footer-text {
      color: #6c757d;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .footer-text:hover {
      color: #495057;
      transform: translateY(-2px);
    }

    .animated-heart {
      display: inline-block;
      font-size: 1.2rem;
      color: #e74c3c;
      animation: heartbeat 1.5s ease-in-out infinite;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .animated-heart:hover {
      color: #c0392b;
      transform: scale(1.3);
      animation-play-state: paused;
    }

    @keyframes heartbeat {
      0% {
        transform: scale(1);
        color: #e74c3c;
      }
      14% {
        transform: scale(1.1);
        color: #ff6b6b;
      }
      28% {
        transform: scale(1);
        color: #e74c3c;
      }
      42% {
        transform: scale(1.1);
        color: #ff6b6b;
      }
      70% {
        transform: scale(1);
        color: #e74c3c;
      }
      100% {
        transform: scale(1);
        color: #e74c3c;
      }
    }

    /* Additional pulse effect */
    .animated-heart::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(231, 76, 60, 0.3) 0%, transparent 70%);
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0);
      animation: pulse 1.5s ease-in-out infinite;
      pointer-events: none;
    }

    @keyframes pulse {
      0% {
        transform: translate(-50%, -50%) scale(0);
        opacity: 1;
      }
      100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
      }
    }

    /* Loading Screen Styles */
    .loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    .loading-screen.fade-out {
      opacity: 0;
      visibility: hidden;
    }

    .loading-content {
      text-align: center;
      color: white;
    }

    .loading-logo {
      font-size: 4rem;
      margin-bottom: 2rem;
      animation: logoPulse 2s ease-in-out infinite;
    }

    @keyframes logoPulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    .loading-text h3 {
      font-size: 2rem;
      margin-bottom: 1rem;
      font-weight: 700;
    }

    .loading-text p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 2rem;
    }

    .loading-bar {
      width: 300px;
      height: 4px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 2px;
      overflow: hidden;
      margin: 0 auto;
    }

    .loading-progress {
      height: 100%;
      background: linear-gradient(90deg, #fff, #f0f0f0);
      border-radius: 2px;
      animation: loadingProgress 3s ease-in-out;
    }

    @keyframes loadingProgress {
      0% { width: 0%; }
      50% { width: 70%; }
      100% { width: 100%; }
    }

    /* Image Carousel Styles */
    .carousel-section {
      padding: 80px 0;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      position: relative;
      overflow: hidden;
    }

    .carousel-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="carouselGrid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="%23e2e8f0" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23carouselGrid)"/></svg>');
      opacity: 0.3;
      z-index: 0;
    }

    .carousel-container {
      position: relative;
      z-index: 1;
      max-width: 1200px;
      margin: 0 auto;
    }

    .carousel-wrapper {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      background: white;
    }

    .carousel-slides {
      position: relative;
      height: 500px;
      overflow: hidden;
    }

    .carousel-slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      transform: translateX(100%);
      transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .carousel-slide.active {
      opacity: 1;
      transform: translateX(0);
    }

    .carousel-slide.prev {
      transform: translateX(-100%);
    }

    .slide-image {
      position: relative;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    .slide-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.8s ease;
    }

    .carousel-slide:hover .slide-image img {
      transform: scale(1.05);
    }

    .slide-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
      color: white;
      padding: 60px 40px 40px;
      transform: translateY(20px);
      opacity: 0;
      transition: all 0.6s ease;
    }

    .carousel-slide.active .slide-overlay {
      transform: translateY(0);
      opacity: 1;
    }

    .slide-overlay h3 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .slide-overlay p {
      font-size: 1.2rem;
      font-weight: 400;
      opacity: 0.9;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .carousel-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.9);
      border: none;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: #333;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .carousel-nav:hover {
      background: white;
      transform: translateY(-50%) scale(1.1);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }

    .carousel-nav.prev {
      left: 20px;
    }

    .carousel-nav.next {
      right: 20px;
    }

    .carousel-dots {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 12px;
      z-index: 10;
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .dot.active {
      background: white;
      transform: scale(1.3);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .dot:hover {
      background: rgba(255, 255, 255, 0.8);
      transform: scale(1.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .carousel-section {
        padding: 60px 0;
      }

      .carousel-slides {
        height: 350px;
      }

      .slide-overlay {
        padding: 40px 20px 20px;
      }

      .slide-overlay h3 {
        font-size: 1.8rem;
      }

      .slide-overlay p {
        font-size: 1rem;
      }

      .carousel-nav {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
      }

      .carousel-nav.prev {
        left: 10px;
      }

      .carousel-nav.next {
        right: 10px;
      }

      .carousel-dots {
        bottom: 15px;
      }

      .dot {
        width: 10px;
        height: 10px;
      }
    }

    @media (max-width: 480px) {
      .carousel-slides {
        height: 250px;
      }

      .slide-overlay h3 {
        font-size: 1.5rem;
      }

      .slide-overlay p {
        font-size: 0.9rem;
      }
    }

    /* Testimonials Section Styles */
    .testimonials-section {
      padding: 80px 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      position: relative;
      overflow: hidden;
    }

    .testimonials-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="testimonialPattern" width="30" height="30" patternUnits="userSpaceOnUse"><circle cx="15" cy="15" r="2" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23testimonialPattern)"/></svg>');
      opacity: 0.3;
    }

    .testimonials-container {
      position: relative;
      z-index: 1;
      max-width: 800px;
      margin: 0 auto;
    }

    .testimonials-slider {
      position: relative;
      overflow: hidden;
    }

    .testimonial-slide {
      display: none;
      animation: fadeIn 0.8s ease-in-out;
    }

    .testimonial-slide.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .testimonial-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 3rem;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .quote-icon {
      font-size: 3rem;
      color: rgba(255, 255, 255, 0.3);
      margin-bottom: 2rem;
    }

    .testimonial-content p {
      font-size: 1.3rem;
      line-height: 1.6;
      margin-bottom: 2rem;
      font-style: italic;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
    }

    .author-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      overflow: hidden;
      border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .author-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .author-info h4 {
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }

    .author-info span {
      opacity: 0.8;
      font-size: 0.9rem;
    }

    .rating {
      color: #ffd700;
      margin-top: 0.5rem;
    }

    .testimonial-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 100%;
      display: flex;
      justify-content: space-between;
      pointer-events: none;
    }

    .testimonial-prev,
    .testimonial-next {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
      transition: all 0.3s ease;
      pointer-events: all;
    }

    .testimonial-prev:hover,
    .testimonial-next:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.1);
    }

    .testimonial-dots {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 2rem;
    }

    .testimonial-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .testimonial-dot.active {
      background: white;
      transform: scale(1.3);
    }

    /* Newsletter Section Styles */
    .newsletter-section {
      padding: 80px 0;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .newsletter-content {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
    }

    .newsletter-text h2 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #333;
    }

    .newsletter-text p {
      font-size: 1.2rem;
      color: #666;
      margin-bottom: 3rem;
    }

    .newsletter-form-container {
      position: relative;
    }

    .newsletter-form .form-group {
      display: flex;
      gap: 1rem;
      max-width: 500px;
      margin: 0 auto;
    }

    .newsletter-form input {
      flex: 1;
      padding: 1rem 1.5rem;
      border: 2px solid #e5e7eb;
      border-radius: 50px;
      font-size: 1rem;
      outline: none;
      transition: all 0.3s ease;
    }

    .newsletter-form input:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .newsletter-btn {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 50px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 120px;
    }

    .newsletter-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .newsletter-success {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      color: #10b981;
      font-weight: 600;
      margin-top: 1rem;
    }

    /* Stats Section Styles */
    .stats-section {
      padding: 80px 0;
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
      color: white;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 3rem;
      text-align: center;
    }

    .stat-item {
      padding: 2rem;
    }

    .stat-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: rgba(255, 255, 255, 0.8);
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
      color: #ffd700;
    }

    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Contact Map Section Styles */
    .contact-map-section {
      padding: 80px 0;
      background: #f8fafc;
    }

    .map-container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .map-placeholder {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 20px;
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .map-placeholder::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="mapPattern" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23mapPattern)"/></svg>');
    }

    .map-content {
      text-align: center;
      color: white;
      position: relative;
      z-index: 1;
    }

    .map-content i {
      font-size: 4rem;
      margin-bottom: 1rem;
      color: #ffd700;
    }

    .map-content h3 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .map-content p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      opacity: 0.9;
    }

    .map-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    .map-btn {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 2px solid rgba(255, 255, 255, 0.3);
      padding: 0.75rem 1.5rem;
      border-radius: 50px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .map-btn:hover {
      background: white;
      color: #667eea;
      transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .testimonial-card {
        padding: 2rem;
      }

      .testimonial-content p {
        font-size: 1.1rem;
      }

      .newsletter-form .form-group {
        flex-direction: column;
      }

      .newsletter-btn {
        width: 100%;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
      }

      .map-actions {
        flex-direction: column;
        align-items: center;
      }
    }

    @media (max-width: 480px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .testimonial-author {
        flex-direction: column;
        text-align: center;
      }
    }

    /* Bombing Animation Styles */
    .bombing-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 10000;
      overflow: hidden;
    }

    .confetti {
      position: absolute;
      width: 10px;
      height: 10px;
      background: #f39c12;
      animation: confetti-fall 3s linear infinite;
    }

    .confetti:nth-child(odd) {
      background: #e74c3c;
      animation-delay: -0.5s;
    }

    .confetti:nth-child(3n) {
      background: #3498db;
      animation-delay: -1s;
    }

    .confetti:nth-child(4n) {
      background: #2ecc71;
      animation-delay: -1.5s;
    }

    .confetti:nth-child(5n) {
      background: #9b59b6;
      animation-delay: -2s;
    }

    @keyframes confetti-fall {
      0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
      }
    }

    .firework {
      position: absolute;
      width: 4px;
      height: 4px;
      border-radius: 50%;
      animation: firework-explode 1.5s ease-out infinite;
    }

    .firework::before,
    .firework::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      animation: firework-explode 1.5s ease-out infinite;
    }

    .firework::before {
      animation-delay: 0.2s;
    }

    .firework::after {
      animation-delay: 0.4s;
    }

    @keyframes firework-explode {
      0% {
        transform: scale(0);
        opacity: 1;
      }
      50% {
        transform: scale(1);
        opacity: 0.8;
      }
      100% {
        transform: scale(2);
        opacity: 0;
      }
    }

    .particle {
      position: absolute;
      width: 6px;
      height: 6px;
      background: radial-gradient(circle, #ffd700, #ff6b6b);
      border-radius: 50%;
      animation: particle-burst 2s ease-out infinite;
    }

    @keyframes particle-burst {
      0% {
        transform: scale(0) translate(0, 0);
        opacity: 1;
      }
      50% {
        transform: scale(1) translate(var(--random-x, 0), var(--random-y, 0));
        opacity: 0.8;
      }
      100% {
        transform: scale(0) translate(var(--random-x, 0), var(--random-y, 0));
        opacity: 0;
      }
    }

    .bombing-trigger {
      position: relative;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .bombing-trigger:hover {
      transform: scale(1.05);
    }

    .bombing-trigger:active {
      transform: scale(0.95);
    }

    .celebration-text {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 4rem;
      font-weight: 900;
      color: #ff6b6b;
      text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
      z-index: 10001;
      pointer-events: none;
      animation: celebration-bounce 3s ease-out;
      text-align: center;
      line-height: 1.2;
    }

    @keyframes celebration-bounce {
      0% {
        transform: translate(-50%, -50%) scale(0) rotate(-10deg);
        opacity: 0;
      }
      20% {
        transform: translate(-50%, -50%) scale(1.3) rotate(5deg);
        opacity: 1;
      }
      40% {
        transform: translate(-50%, -50%) scale(0.9) rotate(-2deg);
        opacity: 1;
      }
      60% {
        transform: translate(-50%, -50%) scale(1.1) rotate(1deg);
        opacity: 1;
      }
      80% {
        transform: translate(-50%, -50%) scale(1) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translate(-50%, -50%) scale(1) rotate(0deg);
        opacity: 0;
      }
    }

    .floating-hearts {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 9999;
    }

    .floating-heart {
      position: absolute;
      font-size: 2rem;
      color: #ff6b6b;
      animation: float-heart 3s ease-out infinite;
    }

    @keyframes float-heart {
      0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(-100vh) rotate(360deg);
        opacity: 0;
      }
    }

    .success-explosion {
      position: relative;
      overflow: hidden;
    }

    .success-explosion::before {
      content: '🎉';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 4rem;
      animation: success-burst 1s ease-out;
      z-index: 10;
    }

    @keyframes success-burst {
      0% {
        transform: translate(-50%, -50%) scale(0);
        opacity: 1;
      }
      50% {
        transform: translate(-50%, -50%) scale(1.5);
        opacity: 0.8;
      }
      100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
      }
    }
  </style>
</head>
<body>
  <!-- Loading Animation -->
  <div id="loadingScreen" class="loading-screen">
    <div class="loading-content">
      <div class="loading-logo">
        <i class="fas fa-school"></i>
      </div>
      <div class="loading-text">
        <h3>Welcome to Our School</h3>
        <p>Loading amazing experience...</p>
      </div>
      <div class="loading-bar">
        <div class="loading-progress"></div>
      </div>
    </div>
  </div>

  <!-- Bombing Animation Container -->
  <div id="bombingContainer" class="bombing-container"></div>
  <div id="floatingHearts" class="floating-hearts"></div>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <h1>🎓 School ERP System</h1>
        <p class="subtitle">
          Transform your educational institution with our comprehensive, modern, and user-friendly school management solution. 
          Streamline operations, enhance learning, and achieve excellence.
        </p>
        
        <div class="btn-group">
        <a href="/login" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-user-shield"></i> SuperAdmin Portal
          </a>
          <a href="/admin/login" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-user-shield"></i> Admin Portal
          </a>
          <a href="/teacher/login" class="btn btn-custom btn-outline-custom">
            <i class="fas fa-chalkboard-teacher"></i> Teacher Portal
          </a>
          <a href="/librarian/login" class="btn btn-custom btn-outline-custom">
            <i class="fas fa-book"></i> Library Portal
          </a>
          <a href="/student/login" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-user-shield"></i> Student Portal
          </a>
          <a href="/parent/login" class="btn btn-custom btn-outline-custom">
            <i class="fas fa-chalkboard-teacher"></i> Parent Portal
          </a>
          <a href="/accountant/login" class="btn btn-custom btn-outline-custom">
            <i class="fas fa-book"></i> Accountant Portal
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Demo Video Section -->
  <section class="demo-section">
    <div class="container">
      <div class="section-title">
        <h2>🎥 See It In Action</h2>
        <p>Watch how our School ERP System transforms educational management</p>
      </div>
      
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="demo-video-container">
            <video id="demoVideo" class="demo-video" controls preload="metadata" style="width: 100%; height: 500px; object-fit: cover;">
              <source src="{{ asset('videos/demo/demo_school_ERP.mp4') }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <div class="demo-overlay" id="demoOverlay">
              <div class="play-button" onclick="playDemo()">
                <i class="fas fa-play"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Portal Access Section -->
  <section class="portals">
    <div class="container">
      <div class="section-title">
        <h2>Access Your Portal</h2>
        <p>Choose your role to access the appropriate dashboard and features</p>
      </div>
      
      <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-crown"></i>
            </div>
            <h4>Super Admin</h4>
            <p>Complete system control and management</p>
          </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/admin/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-user-shield"></i>
            </div>
            <h4>Admin</h4>
            <p>School administration and management</p>
          </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/teacher/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h4>Teacher</h4>
            <p>Teaching tools and student management</p>
          </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/student/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <h4>Student</h4>
            <p>Academic progress and resources</p>
          </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/parent/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-users"></i>
            </div>
            <h4>Parent</h4>
            <p>Monitor your child's progress</p>
          </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/librarian/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-book"></i>
            </div>
            <h4>Librarian</h4>
            <p>Library management and resources</p>
          </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <a href="{{ url('/accountant/login') }}" class="portal-card">
            <div class="portal-icon">
              <i class="fas fa-calculator"></i>
            </div>
            <h4>Accountant</h4>
            <p>Financial management and reports</p>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Image Carousel Section -->
  <section class="carousel-section">
    <div class="container">
      <div class="section-title">
        <h2>Our School in Pictures</h2>
        <p>Discover the beautiful environment where learning comes to life</p>
      </div>
      
      <div class="carousel-container">
        <div class="carousel-wrapper">
          <div class="carousel-slides" id="carouselSlides">
            <div class="carousel-slide active">
              <div class="slide-image">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Modern School Building">
                <div class="slide-overlay">
                  <h3>Modern Infrastructure</h3>
                  <p>State-of-the-art facilities designed for optimal learning</p>
                </div>
              </div>
            </div>
            
            <div class="carousel-slide">
              <div class="slide-image">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Students in Classroom">
                <div class="slide-overlay">
                  <h3>Interactive Learning</h3>
                  <p>Engaging classrooms that inspire creativity and growth</p>
                </div>
              </div>
            </div>
            
            <div class="carousel-slide">
              <div class="slide-image">
                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="School Library">
                <div class="slide-overlay">
                  <h3>Knowledge Hub</h3>
                  <p>Well-stocked library fostering a love for reading</p>
                </div>
              </div>
            </div>
            
            <div class="carousel-slide">
              <div class="slide-image">
                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Science Laboratory">
                <div class="slide-overlay">
                  <h3>Science Laboratory</h3>
                  <p>Advanced labs for hands-on scientific exploration</p>
                </div>
              </div>
            </div>
            
            <div class="carousel-slide">
              <div class="slide-image">
                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Sports Facilities">
                <div class="slide-overlay">
                  <h3>Sports & Recreation</h3>
                  <p>Comprehensive sports facilities for physical development</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Navigation Arrows -->
          <button class="carousel-nav prev" id="prevBtn">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-nav next" id="nextBtn">
            <i class="fas fa-chevron-right"></i>
          </button>
          
          <!-- Dots Indicator -->
          <div class="carousel-dots">
            <span class="dot active" data-slide="0"></span>
            <span class="dot" data-slide="1"></span>
            <span class="dot" data-slide="2"></span>
            <span class="dot" data-slide="3"></span>
            <span class="dot" data-slide="4"></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials-section">
    <div class="container">
      <div class="section-title">
        <h2>What Our Community Says</h2>
        <p>Hear from students, parents, and teachers about their experience</p>
      </div>
      
      <div class="testimonials-container">
        <div class="testimonials-slider" id="testimonialsSlider">
          <div class="testimonial-slide active">
            <div class="testimonial-card">
              <div class="testimonial-content">
                <div class="quote-icon">
                  <i class="fas fa-quote-left"></i>
                </div>
                <p>"This school management system has transformed how we handle daily operations. Everything is so organized and efficient!"</p>
                <div class="testimonial-author">
                  <div class="author-avatar">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="John Smith">
                  </div>
                  <div class="author-info">
                    <h4>John Smith</h4>
                    <span>Parent</span>
                    <div class="rating">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="testimonial-slide">
            <div class="testimonial-card">
              <div class="testimonial-content">
                <div class="quote-icon">
                  <i class="fas fa-quote-left"></i>
                </div>
                <p>"The digital features make teaching so much easier. I can track student progress and communicate with parents seamlessly."</p>
                <div class="testimonial-author">
                  <div class="author-avatar">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Sarah Johnson">
                  </div>
                  <div class="author-info">
                    <h4>Sarah Johnson</h4>
                    <span>Teacher</span>
                    <div class="rating">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="testimonial-slide">
            <div class="testimonial-card">
              <div class="testimonial-content">
                <div class="quote-icon">
                  <i class="fas fa-quote-left"></i>
                </div>
                <p>"I love how easy it is to check my grades and assignments. The mobile app is fantastic!"</p>
                <div class="testimonial-author">
                  <div class="author-avatar">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Mike Wilson">
                  </div>
                  <div class="author-info">
                    <h4>Mike Wilson</h4>
                    <span>Student</span>
                    <div class="rating">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="testimonial-nav">
          <button class="testimonial-prev" id="testimonialPrev">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="testimonial-next" id="testimonialNext">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        
        <div class="testimonial-dots">
          <span class="testimonial-dot active" data-testimonial="0"></span>
          <span class="testimonial-dot" data-testimonial="1"></span>
          <span class="testimonial-dot" data-testimonial="2"></span>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="container">
      <div class="section-title">
        <h2>Why Choose Our System?</h2>
        <p>Comprehensive features designed to meet all your school management needs</p>
      </div>
      
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <h4>Student Management</h4>
            <p>Complete student lifecycle management from admission to graduation with detailed academic records and progress tracking.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h4>Teacher Portal</h4>
            <p>Empower teachers with tools for lesson planning, grade management, attendance tracking, and student communication.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-book"></i>
            </div>
            <h4>Library Management</h4>
            <p>Advanced library system with book cataloging, issue tracking, digital resources, and automated notifications.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <h4>Academic Calendar</h4>
            <p>Comprehensive academic calendar with exam schedules, holidays, events, and automated reminders for all stakeholders.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <h4>Analytics & Reports</h4>
            <p>Powerful analytics dashboard with real-time insights, performance metrics, and customizable reports for data-driven decisions.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-mobile-alt"></i>
            </div>
            <h4>Mobile Responsive</h4>
            <p>Fully responsive design that works seamlessly across all devices - desktop, tablet, and mobile for anytime access.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Feature Highlights Section -->
  <section class="feature-highlights">
    <div class="container">
      <div class="section-title">
        <h2>✨ Key Features</h2>
        <p>Comprehensive modules designed to streamline every aspect of school management</p>
      </div>
      
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="highlight-card">
            <div class="highlight-icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <h5>Online Fee Payment</h5>
            <p>Secure online payment gateway for school fees with instant receipts and payment history tracking.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="highlight-card">
            <div class="highlight-icon">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <h5>Exam & Results Management</h5>
            <p>Complete exam scheduling, grading system, and automated report card generation with detailed analytics.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="highlight-card">
            <div class="highlight-icon">
              <i class="fas fa-bus"></i>
            </div>
            <h5>Transport Tracking</h5>
            <p>Real-time bus tracking, route management, and automated notifications for parents and students.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="highlight-card">
            <div class="highlight-icon">
              <i class="fas fa-bed"></i>
            </div>
            <h5>Hostel Management</h5>
            <p>Complete hostel administration including room allocation, meal planning, and resident management.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="highlight-card">
            <div class="highlight-icon">
              <i class="fas fa-id-card"></i>
            </div>
            <h5>Gate Pass System</h5>
            <p>Digital gate pass management with QR codes, visitor tracking, and automated approval workflows.</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="highlight-card">
            <div class="highlight-icon">
              <i class="fas fa-comments"></i>
            </div>
            <h5>Complaint & Visitor Log</h5>
            <p>Centralized complaint management system with visitor registration and automated response tracking.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="stat-item">
            <div class="stat-number">500+</div>
            <div class="stat-label">Students Managed</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Teachers</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item">
            <div class="stat-number">1000+</div>
            <div class="stat-label">Books in Library</div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item">
            <div class="stat-number">99%</div>
            <div class="stat-label">Satisfaction Rate</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials">
    <div class="container">
      <div class="section-title">
        <h2>What Our Users Say</h2>
        <p>Hear from educators who have transformed their schools with our system</p>
      </div>
      
      <div class="row">
        <div class="col-lg-4">
          <div class="testimonial-card">
            <p class="testimonial-text">
              "This system has revolutionized how we manage our school. The interface is intuitive and the features are exactly what we needed."
            </p>
            <div class="testimonial-author">- Principal Sarah Johnson</div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="testimonial-card">
            <p class="testimonial-text">
              "As a teacher, I love how easy it is to track student progress and communicate with parents. It saves me hours every week."
            </p>
            <div class="testimonial-author">- Teacher Michael Chen</div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="testimonial-card">
            <p class="testimonial-text">
              "The library management features are outstanding. We can now track every book and manage our resources efficiently."
            </p>
            <div class="testimonial-author">- Librarian Emma Davis</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2>🚀 Ready to Transform Your School?</h2>
          <p class="lead">Join hundreds of schools already using our comprehensive ERP system to streamline operations and enhance learning outcomes.</p>
          
          <div class="cta-buttons">
            <a href="#" class="btn-cta btn-cta-primary" onclick="requestDemo()">
              <i class="fas fa-play-circle"></i>
              Request a Demo
            </a>
            <a href="#" class="btn-cta btn-cta-outline" onclick="startTrial()">
              <i class="fas fa-rocket"></i>
              Start Free Trial
            </a>
            <a href="#" class="btn-cta btn-cta-outline" onclick="downloadBrochure()">
              <i class="fas fa-download"></i>
              Download Brochure
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Client Logos Section -->
  <section class="clients-section">
    <div class="container">
      <div class="section-title">
        <h2>🤝 Trusted By Schools</h2>
        <p>Join the growing community of educational institutions using our system</p>
      </div>
      
      <div class="row align-items-center">
        <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
          <img src="https://via.placeholder.com/150x60/667eea/ffffff?text=School+1" alt="School Logo" class="client-logo">
        </div>
        <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
          <img src="https://via.placeholder.com/150x60/764ba2/ffffff?text=School+2" alt="School Logo" class="client-logo">
        </div>
        <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
          <img src="https://via.placeholder.com/150x60/3498db/ffffff?text=School+3" alt="School Logo" class="client-logo">
        </div>
        <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
          <img src="https://via.placeholder.com/150x60/e74c3c/ffffff?text=School+4" alt="School Logo" class="client-logo">
        </div>
        <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
          <img src="https://via.placeholder.com/150x60/f39c12/ffffff?text=School+5" alt="School Logo" class="client-logo">
        </div>
        <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
          <img src="https://via.placeholder.com/150x60/2ecc71/ffffff?text=School+6" alt="School Logo" class="client-logo">
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="faq-section">
    <div class="container">
      <div class="section-title">
        <h2>❓ Frequently Asked Questions</h2>
        <p>Find answers to common questions about our School ERP System</p>
      </div>
      
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
              Is the system mobile friendly?
              <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
              Yes! Our School ERP System is fully responsive and works seamlessly across all devices - desktop computers, tablets, and smartphones. The interface automatically adapts to different screen sizes for optimal user experience.
            </div>
          </div>
          
          <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
              Can parents track their child's progress online?
              <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
              Absolutely! Parents have access to a dedicated portal where they can view their child's academic progress, attendance records, exam results, homework assignments, and communicate directly with teachers.
            </div>
          </div>
          
          <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
              How secure is student data?
              <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
              We take data security very seriously. Our system uses enterprise-grade encryption, secure authentication, regular backups, and complies with educational data protection standards. All data is stored securely and access is strictly controlled.
            </div>
          </div>
          
          <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
              What kind of support do you provide?
              <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
              We provide comprehensive support including 24/7 technical assistance, training sessions for staff, regular system updates, and dedicated account managers to ensure smooth implementation and ongoing success.
            </div>
          </div>
          
          <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
              Can the system be customized for our school's needs?
              <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
              Yes! Our system is highly customizable. We can modify modules, add custom fields, integrate with existing systems, and create school-specific workflows to match your unique requirements and processes.
            </div>
          </div>
          
          <div class="faq-item">
            <button class="faq-question" onclick="toggleFAQ(this)">
              How long does implementation take?
              <i class="fas fa-chevron-down faq-icon"></i>
            </button>
            <div class="faq-answer">
              Implementation typically takes 2-4 weeks depending on your school's size and requirements. This includes data migration, staff training, system configuration, and testing to ensure everything works perfectly.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Newsletter Section -->
  <section class="newsletter-section">
    <div class="container">
      <div class="newsletter-content">
        <div class="newsletter-text">
          <h2>Stay Updated with Our School</h2>
          <p>Subscribe to our newsletter for the latest news, events, and updates</p>
        </div>
        <div class="newsletter-form">
          <form id="newsletterForm" class="newsletter-form-container">
            <div class="form-group">
              <input type="email" id="newsletterEmail" placeholder="Enter your email address" required>
              <button type="submit" class="newsletter-btn">
                <span class="btn-text">Subscribe</span>
                <span class="btn-loading" style="display: none;">
                  <i class="fas fa-spinner fa-spin"></i>
                </span>
              </button>
            </div>
            <div class="newsletter-success success-explosion" style="display: none;">
              <i class="fas fa-check-circle"></i>
              <span>Thank you for subscribing!</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Counter Section -->
  <section class="stats-section">
    <div class="container">
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-number" data-target="2500">0</div>
          <div class="stat-label">Students</div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <div class="stat-number" data-target="150">0</div>
          <div class="stat-label">Teachers</div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div class="stat-number" data-target="95">0</div>
          <div class="stat-label">Success Rate %</div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-award"></i>
          </div>
          <div class="stat-number" data-target="25">0</div>
          <div class="stat-label">Years Experience</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Map Section -->
  <section class="contact-map-section">
    <div class="container">
      <div class="section-title">
        <h2>Visit Our School</h2>
        <p>Find us on the map and plan your visit</p>
      </div>
      <div class="map-container">
        <div class="map-placeholder">
          <div class="map-content">
            <i class="fas fa-map-marker-alt"></i>
            <h3>Our School Location</h3>
            <p>123 Education Street, Learning City, LC 12345</p>
            <div class="map-actions">
              <button class="map-btn">
                <i class="fas fa-directions"></i>
                Get Directions
              </button>
              <button class="map-btn">
                <i class="fas fa-phone"></i>
                Call Us
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h5>School ERP System</h5>
          <p>Empowering educational institutions with modern technology solutions for better learning outcomes and efficient management.</p>
        </div>
        <div class="col-lg-6">
          <h5>Quick Links</h5>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <hr style="border-color: #444; margin: 2rem 0;">
        <p class="mb-1">
            &copy; 2024 <strong>School ERP System</strong>. 
            All rights reserved by <a href="https://khleetsoft.com" target="_blank">KH LeetSoft Innovation</a>.
        </p> 
        <small class="footer-text">
            Built with <span class="animated-heart bombing-trigger" onclick="triggerHeartBombing()">❤️</span> for Education | Powered by Laravel & Bootstrap
        </small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add some interactive animations
    document.addEventListener('DOMContentLoaded', function() {
      // Add floating animation to feature cards
      const featureCards = document.querySelectorAll('.feature-card, .portal-card');
      featureCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
      });
      
      // Add scroll animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);
      
      // Observe all cards
      featureCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
      });
      
      // Add counter animation for stats
      const statNumbers = document.querySelectorAll('.stat-number');
      const animateCounter = (element, target) => {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            current = target;
            clearInterval(timer);
          }
          element.textContent = Math.floor(current) + (target >= 100 ? '+' : '%');
        }, 20);
      };
      
      const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const target = parseInt(entry.target.textContent.replace(/[^\d]/g, ''));
            animateCounter(entry.target, target);
            statsObserver.unobserve(entry.target);
          }
        });
      }, { threshold: 0.5 });
      
      statNumbers.forEach(stat => {
        statsObserver.observe(stat);
      });
    });
    
    // Demo video play function
    function playDemo() {
      const video = document.getElementById('demoVideo');
      const overlay = document.getElementById('demoOverlay');
      
      if (video) {
        console.log('Video element found:', video);
        console.log('Video src:', video.querySelector('source').src);
        
        // Hide the overlay and play the video
        overlay.style.display = 'none';
        
        // Try to play the video
        video.play().then(() => {
          console.log('Video started playing successfully');
        }).catch(error => {
          console.error('Error playing video:', error);
          // Show overlay again if video fails to play
          overlay.style.display = 'flex';
        });
        
        // Show overlay again when video ends
        video.addEventListener('ended', function() {
          overlay.style.display = 'flex';
        });
        
        // Show overlay when video is paused
        video.addEventListener('pause', function() {
          overlay.style.display = 'flex';
        });
      } else {
        console.error('Video element not found');
      }
    }
    
    // CTA button functions
    function requestDemo() {
      alert('Demo request form would open here. Integrate with your contact form or CRM.');
    }
    
    function startTrial() {
      alert('Free trial signup would open here. Integrate with your registration system.');
    }
    
    function downloadBrochure() {
      alert('Brochure download would start here. Add your PDF file and download link.');
    }
    
    // FAQ toggle function
    function toggleFAQ(element) {
      const answer = element.nextElementSibling;
      const icon = element.querySelector('.faq-icon');
      
      if (answer.classList.contains('show')) {
        answer.classList.remove('show');
        icon.classList.remove('rotated');
      } else {
        // Close all other FAQs
        document.querySelectorAll('.faq-answer.show').forEach(item => {
          item.classList.remove('show');
        });
        document.querySelectorAll('.faq-icon.rotated').forEach(item => {
          item.classList.remove('rotated');
        });
        
        // Open current FAQ
        answer.classList.add('show');
        icon.classList.add('rotated');
      }
    }

    // Add heart interaction
    const animatedHeart = document.querySelector('.animated-heart');
    if (animatedHeart) {
      animatedHeart.addEventListener('click', function() {
        // Create floating hearts effect
        createFloatingHearts();
        
        // Add special animation
        this.style.animation = 'none';
        this.style.transform = 'scale(1.5)';
        this.style.color = '#ff6b6b';
        
        setTimeout(() => {
          this.style.animation = 'heartbeat 1.5s ease-in-out infinite';
          this.style.transform = 'scale(1)';
          this.style.color = '#e74c3c';
        }, 500);
      });
    }

    function createFloatingHearts() {
      const heart = document.querySelector('.animated-heart');
      const rect = heart.getBoundingClientRect();
      
      for (let i = 0; i < 5; i++) {
        const floatingHeart = document.createElement('div');
        floatingHeart.innerHTML = '❤️';
        floatingHeart.style.position = 'fixed';
        floatingHeart.style.left = rect.left + 'px';
        floatingHeart.style.top = rect.top + 'px';
        floatingHeart.style.fontSize = '20px';
        floatingHeart.style.color = '#e74c3c';
        floatingHeart.style.pointerEvents = 'none';
        floatingHeart.style.zIndex = '9999';
        floatingHeart.style.animation = `floatUp ${2 + Math.random()}s ease-out forwards`;
        
        document.body.appendChild(floatingHeart);
        
        setTimeout(() => {
          floatingHeart.remove();
        }, 3000);
      }
    }

    // Add CSS for floating hearts animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes floatUp {
        0% {
          transform: translateY(0) rotate(0deg);
          opacity: 1;
        }
        100% {
          transform: translateY(-100px) rotate(360deg);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);

    // Carousel functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const totalSlides = slides.length;

    function showSlide(index) {
      // Remove active class from all slides and dots
      slides.forEach(slide => slide.classList.remove('active', 'prev'));
      dots.forEach(dot => dot.classList.remove('active'));

      // Add active class to current slide and dot
      slides[index].classList.add('active');
      dots[index].classList.add('active');

      // Add prev class to previous slide for smooth transition
      const prevIndex = index === 0 ? totalSlides - 1 : index - 1;
      slides[prevIndex].classList.add('prev');
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      showSlide(currentSlide);
    }

    function prevSlide() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      showSlide(currentSlide);
    }

    function goToSlide(index) {
      currentSlide = index;
      showSlide(currentSlide);
    }

    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // Dot navigation
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => goToSlide(index));
    });

    // Auto-play carousel
    let autoPlayInterval = setInterval(nextSlide, 5000);

    // Pause auto-play on hover
    const carouselContainer = document.querySelector('.carousel-container');
    if (carouselContainer) {
      carouselContainer.addEventListener('mouseenter', () => {
        clearInterval(autoPlayInterval);
      });

      carouselContainer.addEventListener('mouseleave', () => {
        autoPlayInterval = setInterval(nextSlide, 5000);
      });
    }

    // Touch/swipe support for mobile
    let startX = 0;
    let endX = 0;

    if (carouselContainer) {
      carouselContainer.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
      });

      carouselContainer.addEventListener('touchend', (e) => {
        endX = e.changedTouches[0].clientX;
        handleSwipe();
      });
    }

    function handleSwipe() {
      const threshold = 50;
      const diff = startX - endX;

      if (Math.abs(diff) > threshold) {
        if (diff > 0) {
          nextSlide(); // Swipe left - next slide
        } else {
          prevSlide(); // Swipe right - previous slide
        }
      }
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        prevSlide();
      } else if (e.key === 'ArrowRight') {
        nextSlide();
      }
    });

    // Initialize carousel
    showSlide(0);

    // Loading screen functionality
    window.addEventListener('load', function() {
      const loadingScreen = document.getElementById('loadingScreen');
      setTimeout(() => {
        loadingScreen.classList.add('fade-out');
        setTimeout(() => {
          loadingScreen.style.display = 'none';
        }, 500);
      }, 3000);
    });

    // Testimonials functionality
    let currentTestimonial = 0;
    const testimonialSlides = document.querySelectorAll('.testimonial-slide');
    const testimonialDots = document.querySelectorAll('.testimonial-dot');
    const testimonialPrev = document.getElementById('testimonialPrev');
    const testimonialNext = document.getElementById('testimonialNext');
    const totalTestimonials = testimonialSlides.length;

    function showTestimonial(index) {
      testimonialSlides.forEach(slide => slide.classList.remove('active'));
      testimonialDots.forEach(dot => dot.classList.remove('active'));
      
      testimonialSlides[index].classList.add('active');
      testimonialDots[index].classList.add('active');
    }

    function nextTestimonial() {
      currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
      showTestimonial(currentTestimonial);
    }

    function prevTestimonial() {
      currentTestimonial = (currentTestimonial - 1 + totalTestimonials) % totalTestimonials;
      showTestimonial(currentTestimonial);
    }

    if (testimonialNext) testimonialNext.addEventListener('click', nextTestimonial);
    if (testimonialPrev) testimonialPrev.addEventListener('click', prevTestimonial);

    testimonialDots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        currentTestimonial = index;
        showTestimonial(currentTestimonial);
      });
    });

    // Auto-play testimonials
    setInterval(nextTestimonial, 6000);

    // Newsletter functionality
    const newsletterForm = document.getElementById('newsletterForm');
    const newsletterEmail = document.getElementById('newsletterEmail');
    const newsletterBtn = document.querySelector('.newsletter-btn');
    const btnText = newsletterBtn.querySelector('.btn-text');
    const btnLoading = newsletterBtn.querySelector('.btn-loading');
    const newsletterSuccess = document.querySelector('.newsletter-success');

    if (newsletterForm) {
      newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        newsletterBtn.disabled = true;

        // Simulate API call
        setTimeout(() => {
          // Hide loading state
          btnText.style.display = 'inline';
          btnLoading.style.display = 'none';
          newsletterBtn.disabled = false;

          // Show success message
          newsletterSuccess.style.display = 'flex';
          newsletterEmail.value = '';

          // Hide success message after 5 seconds
          setTimeout(() => {
            newsletterSuccess.style.display = 'none';
          }, 5000);
        }, 2000);
      });
    }

    // Stats counter animation
    function animateCounter(element, target, duration = 2000) {
      let start = 0;
      const increment = target / (duration / 16);
      
      function updateCounter() {
        start += increment;
        if (start < target) {
          element.textContent = Math.floor(start);
          requestAnimationFrame(updateCounter);
        } else {
          element.textContent = target;
        }
      }
      
      updateCounter();
    }

    // Intersection Observer for stats animation
    const statsObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const statNumbers = entry.target.querySelectorAll('.stat-number');
          statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-target'));
            animateCounter(stat, target);
          });
          statsObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
      statsObserver.observe(statsSection);
    }

    // Map button functionality
    const mapBtns = document.querySelectorAll('.map-btn');
    mapBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        if (this.textContent.includes('Directions')) {
          // Open Google Maps with school location
          window.open('https://maps.google.com/?q=123+Education+Street,+Learning+City,+LC+12345', '_blank');
        } else if (this.textContent.includes('Call')) {
          // Open phone dialer
          window.location.href = 'tel:+1234567890';
        }
      });
    });

    // Bombing Animation Functions
    function triggerWelcomeBombing() {
      createConfetti();
      createFireworks();
      createParticles();
      createFloatingHearts();
      showCelebrationText('🎓 Welcome to School ERP! 🎓');
    }

    function triggerHeartBombing() {
      createFloatingHearts();
      createConfetti();
      showCelebrationText('💖 LOVE! 💖');
    }

    function createConfetti() {
      const container = document.getElementById('bombingContainer');
      const colors = ['#f39c12', '#e74c3c', '#3498db', '#2ecc71', '#9b59b6', '#ff6b6b', '#ffd700'];
      
      for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.animationDelay = Math.random() * 3 + 's';
        confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.width = (Math.random() * 10 + 5) + 'px';
        confetti.style.height = confetti.style.width;
        container.appendChild(confetti);
        
        setTimeout(() => {
          confetti.remove();
        }, 3000);
      }
    }

    function createFireworks() {
      const container = document.getElementById('bombingContainer');
      const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3'];
      
      for (let i = 0; i < 15; i++) {
        const firework = document.createElement('div');
        firework.className = 'firework';
        firework.style.left = Math.random() * 100 + '%';
        firework.style.top = Math.random() * 100 + '%';
        firework.style.background = colors[Math.floor(Math.random() * colors.length)];
        firework.style.animationDelay = Math.random() * 2 + 's';
        container.appendChild(firework);
        
        setTimeout(() => {
          firework.remove();
        }, 2000);
      }
    }

    function createParticles() {
      const container = document.getElementById('bombingContainer');
      const colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1'];
      
      for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.background = `radial-gradient(circle, ${colors[Math.floor(Math.random() * colors.length)]}, ${colors[Math.floor(Math.random() * colors.length)]})`;
        particle.style.setProperty('--random-x', (Math.random() - 0.5) * 200 + 'px');
        particle.style.setProperty('--random-y', (Math.random() - 0.5) * 200 + 'px');
        particle.style.animationDelay = Math.random() * 2 + 's';
        container.appendChild(particle);
        
        setTimeout(() => {
          particle.remove();
        }, 2000);
      }
    }

    function createFloatingHearts() {
      const container = document.getElementById('floatingHearts');
      const hearts = ['❤️', '💖', '💕', '💗', '💝', '💘', '💞', '💓'];
      
      for (let i = 0; i < 20; i++) {
        const heart = document.createElement('div');
        heart.className = 'floating-heart';
        heart.textContent = hearts[Math.floor(Math.random() * hearts.length)];
        heart.style.left = Math.random() * 100 + '%';
        heart.style.animationDelay = Math.random() * 3 + 's';
        heart.style.fontSize = (Math.random() * 2 + 1) + 'rem';
        container.appendChild(heart);
        
        setTimeout(() => {
          heart.remove();
        }, 3000);
      }
    }

    function showCelebrationText(text) {
      const celebration = document.createElement('div');
      celebration.className = 'celebration-text';
      celebration.textContent = text;
      document.body.appendChild(celebration);
      
      setTimeout(() => {
        celebration.remove();
      }, 2000);
    }

    // Welcome bombing animation on page load (after loading screen)
    window.addEventListener('load', function() {
      setTimeout(() => {
        triggerWelcomeBombing();
      }, 4000);
    });

    // Random bombing animations during page interaction
    let interactionCount = 0;
    document.addEventListener('click', function() {
      interactionCount++;
      if (interactionCount % 10 === 0) { // Every 10th click
        if (Math.random() > 0.5) {
          createConfetti();
        }
      }
    });

    // Special bombing for newsletter success
    const originalNewsletterSuccess = document.querySelector('.newsletter-success');
    if (originalNewsletterSuccess) {
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
            if (originalNewsletterSuccess.style.display === 'flex') {
              setTimeout(() => {
                triggerWelcomeBombing();
              }, 500);
            }
          }
        });
      });
      observer.observe(originalNewsletterSuccess, { attributes: true });
    }
  </script>
</body>
</html>