@extends('admin.layout.app')

@section('content')
<style>
    /* Import Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    
    * {
        font-family: 'Inter', sans-serif;
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    .main-container {
        background: transparent;
        padding: 2rem 0;
    }
    
    .student-profile-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        border: none;
        border-radius: 30px;
        box-shadow: 
            0 20px 60px rgba(0,0,0,0.1),
            0 8px 25px rgba(0,0,0,0.05),
            inset 0 1px 0 rgba(255,255,255,0.8);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        backdrop-filter: blur(10px);
    }
    
    .student-profile-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 
            0 30px 80px rgba(0,0,0,0.15),
            0 15px 35px rgba(0,0,0,0.1),
            inset 0 1px 0 rgba(255,255,255,0.9);
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        color: white;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        border-radius: 30px 30px 0 0;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite;
    }
    
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite reverse;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
        50% { transform: translateY(-30px) rotate(180deg) scale(1.1); }
    }
    
    .profile-picture-container {
        position: relative;
        z-index: 3;
        text-align: center;
    }
    
    .profile-picture-container img {
        border: 6px solid rgba(255,255,255,0.4);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 
            0 15px 35px rgba(0,0,0,0.3),
            0 5px 15px rgba(0,0,0,0.2);
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }
    
    .profile-picture-container:hover img {
        transform: scale(1.08) rotate(2deg);
        border-color: rgba(255,255,255,0.8);
        box-shadow: 
            0 20px 40px rgba(0,0,0,0.4),
            0 10px 20px rgba(0,0,0,0.3);
    }
    
    .profile-name {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 2.2rem;
        margin: 1.5rem 0 0.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        position: relative;
        z-index: 2;
    }
    
    .profile-id {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 500;
        position: relative;
        z-index: 2;
    }
    
    .profile-badges {
        margin-top: 1.5rem;
        position: relative;
        z-index: 2;
    }
    
    .badge-modern {
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0 0.5rem 0.5rem 0;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-modern:hover {
        background: rgba(255,255,255,0.4);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .stats-card {
        background: linear-gradient(145deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px) scale(1.05);
        background: linear-gradient(145deg, rgba(255,255,255,0.3), rgba(255,255,255,0.2));
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 5s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
        50% { transform: scale(1.2) rotate(180deg); opacity: 0.8; }
    }
    
    .stats-number {
        font-family: 'Poppins', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    
    .stats-label {
        font-size: 0.95rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        z-index: 2;
        font-weight: 600;
    }
    
    .content-section {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 0 0 30px 30px;
        padding: 3rem;
        position: relative;
    }
    
    .section-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        border-radius: 25px;
        box-shadow: 
            0 15px 35px rgba(0,0,0,0.08),
            0 5px 15px rgba(0,0,0,0.05),
            inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        margin-bottom: 2.5rem;
        position: relative;
    }
    
    .section-card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 
            0 25px 50px rgba(0,0,0,0.12),
            0 10px 25px rgba(0,0,0,0.08),
            inset 0 1px 0 rgba(255,255,255,0.9);
    }
    
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        color: white;
        padding: 2rem;
        margin: 0;
        border-radius: 25px 25px 0 0;
        position: relative;
        overflow: hidden;
    }
    
    .section-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .section-header h5 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.4rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    
    .section-header i {
        margin-right: 12px;
        font-size: 1.5rem;
        position: relative;
        z-index: 2;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 0;
    }
    
    .info-item {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
    }
    
    .info-item:nth-child(even) {
        background: linear-gradient(145deg, #f8f9ff 0%, #ffffff 100%);
    }
    
    .info-item:hover {
        background: linear-gradient(145deg, #e3f2fd 0%, #f3e5f5 100%);
        padding-left: 2rem;
        transform: translateX(5px);
        box-shadow: inset 5px 0 0 #667eea;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-family: 'Poppins', sans-serif;
    }
    
    .info-label i {
        margin-right: 8px;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    
    .info-value {
        color: #1f2937;
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.5;
    }
    
    .badge-custom {
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .badge-custom:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .btn-custom {
        border-radius: 30px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        position: relative;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
    }
    
    .btn-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s;
    }
    
    .btn-custom:hover::before {
        left: 100%;
    }
    
    .btn-custom:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary-custom:hover {
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.6);
    }
    
    .btn-secondary-custom {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
    }
    
    .btn-secondary-custom:hover {
        box-shadow: 0 15px 30px rgba(240, 147, 251, 0.6);
    }
    
    .action-buttons {
        text-align: center;
        margin-top: 3rem;
        padding: 2rem;
        background: linear-gradient(145deg, rgba(255,255,255,0.8), rgba(248,249,255,0.8));
        backdrop-filter: blur(10px);
        border-radius: 25px;
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .btn-group-modern {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(30px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }
    
    .slide-in-left {
        animation: slideInLeft 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes slideInLeft {
        from { 
            opacity: 0; 
            transform: translateX(-50px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0) scale(1); 
        }
    }
    
    .slide-in-right {
        animation: slideInRight 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes slideInRight {
        from { 
            opacity: 0; 
            transform: translateX(50px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0) scale(1); 
        }
    }
    
    .stagger-animation {
        animation: staggerIn 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .stagger-animation:nth-child(1) { animation-delay: 0.1s; }
    .stagger-animation:nth-child(2) { animation-delay: 0.2s; }
    .stagger-animation:nth-child(3) { animation-delay: 0.3s; }
    .stagger-animation:nth-child(4) { animation-delay: 0.4s; }
    .stagger-animation:nth-child(5) { animation-delay: 0.5s; }
    .stagger-animation:nth-child(6) { animation-delay: 0.6s; }
    
    @keyframes staggerIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 1rem;
        }
        
        .profile-name {
            font-size: 1.8rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .btn-group-modern {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-custom {
            width: 100%;
            max-width: 300px;
        }
    }
    
    /* Loading Animation */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    /* Modern Tabbed Interface */
    .modern-tabs-container {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .tab-navigation {
        display: flex;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        padding: 0;
        margin: 0;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .tab-navigation::-webkit-scrollbar {
        display: none;
    }
    
    .tab-nav-item {
        flex: 1;
        min-width: 120px;
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        color: rgba(255,255,255,0.8);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .tab-nav-item:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        transform: translateY(-2px);
    }
    
    .tab-nav-item.active {
        background: rgba(255,255,255,0.2);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .tab-nav-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: white;
        border-radius: 3px 3px 0 0;
    }
    
    .tab-nav-item i {
        font-size: 1.2rem;
        margin-bottom: 0.3rem;
    }
    
    .tab-content-container {
        padding: 0;
        background: transparent;
    }
    
    .tab-content {
        display: none;
        padding: 2rem;
        animation: fadeInUp 0.5s ease-out;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Documents Grid */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    .document-item {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .document-item:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }
    
    .document-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }
    
    .document-info {
        flex: 1;
    }
    
    .document-info h6 {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: #2c3e50;
        font-size: 1rem;
    }
    
    .document-info p {
        margin: 0 0 0.8rem 0;
        color: #6c757d;
        font-size: 0.85rem;
        line-height: 1.4;
    }
    
    .document-status {
        font-size: 0.75rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Success Button */
    .btn-success-custom {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }
    
    .btn-success-custom:hover {
        box-shadow: 0 15px 30px rgba(40, 167, 69, 0.6);
    }
    
    /* Responsive Tab Navigation */
    @media (max-width: 768px) {
        .tab-nav-item {
            min-width: 100px;
            padding: 1rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .tab-nav-item i {
            font-size: 1rem;
        }
        
        .tab-content {
            padding: 1rem;
        }
        
        .documents-grid {
            grid-template-columns: 1fr;
        }
        
        .document-item {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabItems = document.querySelectorAll('.tab-nav-item');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabItems.forEach(item => {
        item.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabItems.forEach(tab => tab.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
    
    // Print functionality
    window.printStudentDetails = function() {
        const printContent = document.querySelector('.student-profile-card').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = `
            <html>
                <head>
                    <title>Student Details - {{ $details->user->name ?? 'Student' }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .profile-header { background: #667eea; color: white; padding: 20px; border-radius: 10px; }
                        .section-card { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
                        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px; }
                        .info-item { padding: 10px; border-bottom: 1px solid #f0f0f0; }
                        .info-label { font-weight: bold; color: #333; }
                        .info-value { color: #666; }
                        .tab-content { display: block !important; }
                        .tab-navigation { display: none; }
                        .action-buttons { display: none; }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    };
});
</script>

<div class="container-fluid main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card student-profile-card fade-in">
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center slide-in-left">
                            <div class="profile-picture-container">
                                @if($details->profile_picture)
                                    <img src="{{ asset('storage/' . $details->profile_picture) }}" 
                                         alt="Student Profile" 
                                         class="img-fluid rounded-circle" 
                                         style="width: 180px; height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 180px; height: 180px; margin: 0 auto; box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
                                        <i class="fas fa-user fa-4x text-primary"></i>
                                    </div>
                                @endif
                                <h2 class="profile-name">{{ $details->user->name ?? '-' }}</h2>
                                <p class="profile-id">{{ $details->admission_no }}</p>
                                <div class="profile-badges">
                                    <span class="badge-modern">{{ $details->class->name ?? '-' }}</span>
                                    <span class="badge-modern">{{ $details->section->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 slide-in-right">
                            <div class="stats-grid">
                                <div class="stats-card stagger-animation">
                                    <div class="stats-number">{{ $details->overall_percentage ?? '0' }}%</div>
                                    <div class="stats-label">Overall Performance</div>
                                </div>
                                <div class="stats-card stagger-animation">
                                    <div class="stats-number">{{ $details->attendance_percentage ?? '0' }}%</div>
                                    <div class="stats-label">Attendance</div>
                                </div>
                                <div class="stats-card stagger-animation">
                                    <div class="stats-number">{{ $details->class_rank ?? '-' }}</div>
                                    <div class="stats-label">Class Rank</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-section">
                    <!-- Modern Tabbed Interface -->
                    <div class="modern-tabs-container">
                        <div class="tab-navigation">
                            <div class="tab-nav-item active" data-tab="profile">
                                <i class="fas fa-user-graduate"></i>
                                <span>Profile</span>
                            </div>
                            <div class="tab-nav-item" data-tab="academic">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Academic</span>
                            </div>
                            <div class="tab-nav-item" data-tab="parent">
                                <i class="fas fa-users"></i>
                                <span>Parents</span>
                            </div>
                            <div class="tab-nav-item" data-tab="transport">
                                <i class="fas fa-bus"></i>
                                <span>Transport</span>
                            </div>
                            <div class="tab-nav-item" data-tab="hostel">
                                <i class="fas fa-bed"></i>
                                <span>Hostel</span>
                            </div>
                            <div class="tab-nav-item" data-tab="performance">
                                <i class="fas fa-chart-line"></i>
                                <span>Performance</span>
                            </div>
                            <div class="tab-nav-item" data-tab="documents">
                                <i class="fas fa-file-alt"></i>
                                <span>Documents</span>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content-container">
                            <!-- Profile Tab -->
                            <div class="tab-content active" id="profile">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-user-graduate"></i> Basic Information</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-envelope text-primary"></i> Email Address</span>
                                            <div class="info-value">{{ $details->user->email ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-id-card text-primary"></i> Roll Number</span>
                                            <div class="info-value">{{ $details->roll_no }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-calendar text-primary"></i> Date of Birth</span>
                                            <div class="info-value">{{ $details->dob }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-venus-mars text-primary"></i> Gender</span>
                                            <div class="info-value">{{ ucfirst($details->gender) }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-tint text-primary"></i> Blood Group</span>
                                            <div class="info-value">{{ $details->blood_group }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-pray text-primary"></i> Religion</span>
                                            <div class="info-value">{{ $details->religion }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-flag text-primary"></i> Nationality</span>
                                            <div class="info-value">{{ $details->nationality }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-tags text-primary"></i> Category</span>
                                            <div class="info-value">{{ $details->category }}</div>
                                        </div>
                                        <div class="info-item" style="grid-column: 1 / -1;">
                                            <span class="info-label"><i class="fas fa-map-marker-alt text-primary"></i> Address</span>
                                            <div class="info-value">{{ $details->address }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Details -->
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-map-marker-alt"></i> Address Details</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-home text-success"></i> Current Address</span>
                                            <div class="info-value">{{ $details->current_address ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-building text-info"></i> Permanent Address</span>
                                            <div class="info-value">{{ $details->permanent_address ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-city text-warning"></i> City</span>
                                            <div class="info-value">{{ $details->city ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-map text-danger"></i> State</span>
                                            <div class="info-value">{{ $details->state ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-mail-bulk text-primary"></i> Postal Code</span>
                                            <div class="info-value">{{ $details->postal_code ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-globe text-success"></i> Country</span>
                                            <div class="info-value">{{ $details->country ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Tab -->
                            <div class="tab-content" id="academic">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-calendar-alt text-info"></i> Academic Year</span>
                                            <div class="info-value">{{ $details->academic_year ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-university text-info"></i> Exam Board</span>
                                            <div class="info-value">{{ $details->exam_board ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-calendar-check text-info"></i> Last Exam Date</span>
                                            <div class="info-value">{{ $details->last_exam_date ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-calendar-plus text-info"></i> Next Exam Date</span>
                                            <div class="info-value">{{ $details->next_exam_date ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-map-marker-alt text-info"></i> Exam Center</span>
                                            <div class="info-value">{{ $details->exam_center ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-ticket-alt text-info"></i> Hall Ticket No</span>
                                            <div class="info-value">{{ $details->hall_ticket_no ?? '-' }}</div>
                                        </div>
                                        <div class="info-item" style="grid-column: 1 / -1;">
                                            <span class="info-label"><i class="fas fa-book text-info"></i> Exam Subjects</span>
                                            <div class="info-value">{{ $details->exam_subjects ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Tab -->
                            <div class="tab-content" id="parent">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-users"></i> Parent/Guardian Details</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-male text-success"></i> Father's Name</span>
                                            <div class="info-value">{{ $details->father_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-briefcase text-success"></i> Father's Occupation</span>
                                            <div class="info-value">{{ $details->father_occupation ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-phone text-success"></i> Father's Contact</span>
                                            <div class="info-value">{{ $details->father_contact ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-envelope text-success"></i> Father's Email</span>
                                            <div class="info-value">{{ $details->father_email ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-female text-danger"></i> Mother's Name</span>
                                            <div class="info-value">{{ $details->mother_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-briefcase text-danger"></i> Mother's Occupation</span>
                                            <div class="info-value">{{ $details->mother_occupation ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-phone text-danger"></i> Mother's Contact</span>
                                            <div class="info-value">{{ $details->mother_contact ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-envelope text-danger"></i> Mother's Email</span>
                                            <div class="info-value">{{ $details->mother_email ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-user-shield text-warning"></i> Guardian Name</span>
                                            <div class="info-value">{{ $details->guardian_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-link text-warning"></i> Guardian Relation</span>
                                            <div class="info-value">{{ $details->guardian_relation ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-phone text-warning"></i> Guardian Contact</span>
                                            <div class="info-value">{{ $details->guardian_contact ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-envelope text-warning"></i> Guardian Email</span>
                                            <div class="info-value">{{ $details->guardian_email ?? '-' }}</div>
                                        </div>
                                        <div class="info-item" style="grid-column: 1 / -1;">
                                            <span class="info-label"><i class="fas fa-exclamation-triangle text-danger"></i> Emergency Contact</span>
                                            <div class="info-value">{{ $details->emergency_contact ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Transport Tab -->
                            <div class="tab-content" id="transport">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-bus"></i> Transport Details</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-circle text-success"></i> Transport Status</span>
                                            <div class="info-value">
                                                <span class="badge badge-custom {{ $details->transport_status == 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ ucfirst($details->transport_status ?? 'Not Assigned') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-route text-primary"></i> Route Name</span>
                                            <div class="info-value">{{ $details->route_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-car text-info"></i> Vehicle Number</span>
                                            <div class="info-value">{{ $details->vehicle_number ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-user-tie text-warning"></i> Driver Name</span>
                                            <div class="info-value">{{ $details->driver_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-phone text-success"></i> Driver Contact</span>
                                            <div class="info-value">{{ $details->driver_contact ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-map-pin text-danger"></i> Pickup Point</span>
                                            <div class="info-value">{{ $details->pickup_point ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-clock text-primary"></i> Pickup Time</span>
                                            <div class="info-value">{{ $details->pickup_time ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-clock text-info"></i> Drop Time</span>
                                            <div class="info-value">{{ $details->drop_time ?? '-' }}</div>
                                        </div>
                                        <div class="info-item" style="grid-column: 1 / -1;">
                                            <span class="info-label"><i class="fas fa-rupee-sign text-success"></i> Monthly Fee</span>
                                            <div class="info-value">
                                                @if($details->transport_fee)
                                                    ₹{{ number_format($details->transport_fee, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hostel Tab -->
                            <div class="tab-content" id="hostel">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-bed"></i> Hostel Details</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-circle text-success"></i> Hostel Status</span>
                                            <div class="info-value">
                                                <span class="badge badge-custom {{ $details->hostel_status == 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ ucfirst($details->hostel_status ?? 'Not Assigned') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-building text-primary"></i> Hostel Name</span>
                                            <div class="info-value">{{ $details->hostel_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-door-open text-info"></i> Room Number</span>
                                            <div class="info-value">{{ $details->room_number ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-layer-group text-warning"></i> Floor</span>
                                            <div class="info-value">{{ $details->floor ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-cube text-danger"></i> Block</span>
                                            <div class="info-value">{{ $details->block ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-home text-success"></i> Room Type</span>
                                            <div class="info-value">{{ ucfirst($details->room_type ?? '-') }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-user-shield text-primary"></i> Warden Name</span>
                                            <div class="info-value">{{ $details->warden_name ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-phone text-info"></i> Warden Contact</span>
                                            <div class="info-value">{{ $details->warden_contact ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-calendar-check text-success"></i> Check-in Date</span>
                                            <div class="info-value">{{ $details->checkin_date ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-rupee-sign text-warning"></i> Monthly Fee</span>
                                            <div class="info-value">
                                                @if($details->hostel_fee)
                                                    ₹{{ number_format($details->hostel_fee, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-item" style="grid-column: 1 / -1;">
                                            <span class="info-label"><i class="fas fa-sticky-note text-secondary"></i> Special Instructions</span>
                                            <div class="info-value">{{ $details->hostel_instructions ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Performance Tab -->
                            <div class="tab-content" id="performance">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-chart-line"></i> Academic Performance</h5>
                                    </div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-star text-warning"></i> Overall Grade</span>
                                            <div class="info-value">
                                                <span class="badge badge-custom badge-{{ $details->overall_grade == 'A+' ? 'success' : ($details->overall_grade == 'A' ? 'primary' : ($details->overall_grade == 'B' ? 'warning' : 'secondary')) }}">
                                                    {{ $details->overall_grade ?? 'Not Available' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-percentage text-primary"></i> Overall Percentage</span>
                                            <div class="info-value">{{ $details->overall_percentage ?? '-' }}%</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-user-check text-success"></i> Attendance</span>
                                            <div class="info-value">{{ $details->attendance_percentage ?? '-' }}%</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-trophy text-warning"></i> Class Rank</span>
                                            <div class="info-value">{{ $details->class_rank ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-calculator text-info"></i> Total Marks</span>
                                            <div class="info-value">{{ $details->total_marks ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-check-circle text-success"></i> Obtained Marks</span>
                                            <div class="info-value">{{ $details->obtained_marks ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-medal text-primary"></i> Last Exam Result</span>
                                            <div class="info-value">{{ $details->last_exam_result ?? '-' }}</div>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-chart-bar text-info"></i> Performance Status</span>
                                            <div class="info-value">
                                                <span class="badge badge-custom badge-{{ $details->performance_status == 'excellent' ? 'success' : ($details->performance_status == 'good' ? 'primary' : ($details->performance_status == 'average' ? 'warning' : 'secondary')) }}">
                                                    {{ ucfirst($details->performance_status ?? 'Not Available') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item" style="grid-column: 1 / -1;">
                                            <span class="info-label"><i class="fas fa-comment text-secondary"></i> Academic Remarks</span>
                                            <div class="info-value">{{ $details->academic_remarks ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Tab -->
                            <div class="tab-content" id="documents">
                                <div class="section-card stagger-animation">
                                    <div class="section-header">
                                        <h5><i class="fas fa-file-alt"></i> Documents & Files</h5>
                                    </div>
                                    <div class="documents-grid">
                                        <div class="document-item">
                                            <div class="document-icon">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                            </div>
                                            <div class="document-info">
                                                <h6>Academic Transcript</h6>
                                                <p>Latest academic performance record</p>
                                                <span class="document-status badge badge-success">Available</span>
                                            </div>
                                        </div>
                                        <div class="document-item">
                                            <div class="document-icon">
                                                <i class="fas fa-id-card text-primary"></i>
                                            </div>
                                            <div class="document-info">
                                                <h6>Student ID Card</h6>
                                                <p>Official student identification</p>
                                                <span class="document-status badge badge-success">Available</span>
                                            </div>
                                        </div>
                                        <div class="document-item">
                                            <div class="document-icon">
                                                <i class="fas fa-certificate text-warning"></i>
                                            </div>
                                            <div class="document-info">
                                                <h6>Birth Certificate</h6>
                                                <p>Official birth documentation</p>
                                                <span class="document-status badge badge-warning">Pending</span>
                                            </div>
                                        </div>
                                        <div class="document-item">
                                            <div class="document-icon">
                                                <i class="fas fa-image text-info"></i>
                                            </div>
                                            <div class="document-info">
                                                <h6>Profile Photo</h6>
                                                <p>Student profile picture</p>
                                                <span class="document-status badge badge-success">Available</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons stagger-animation">
                        <div class="btn-group-modern">
                            <a href="{{ route('admin.students.details.edit', $details->id) }}" class="btn btn-custom btn-primary-custom">
                                <i class="fas fa-edit"></i> Edit Details
                            </a>
                            <a href="{{ route('admin.students.details.index') }}" class="btn btn-custom btn-secondary-custom">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <button class="btn btn-custom btn-success-custom" onclick="printStudentDetails()">
                                <i class="fas fa-print"></i> Print Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
