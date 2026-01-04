@extends('admin.layout.app')

@section('title', 'Amazing Sidebar Design')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🚀 AMAZING SIDEBAR DESIGN</h3>
                    <p class="text-muted">Experience the most stunning and modern sidebar design ever created!</p>
                </div>
                <div class="card-body">
                    <!-- Hero Section -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="hero-section text-center p-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; color: white;">
                                <h1 class="display-4 mb-3">🎨 AMAZING SIDEBAR</h1>
                                <p class="lead mb-4">The most beautiful and interactive sidebar design with incredible animations and effects!</p>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <i class="fas fa-palette fa-3x mb-3"></i>
                                        <h5>Stunning Design</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-magic fa-3x mb-3"></i>
                                        <h5>Amazing Effects</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-rocket fa-3x mb-3"></i>
                                        <h5>Lightning Fast</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-mobile-alt fa-3x mb-3"></i>
                                        <h5>Responsive</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Grid -->
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 feature-card">
                                <div class="card-body text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-gradient fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">Animated Gradient</h5>
                                    <p class="card-text">Beautiful multi-color gradient that shifts and moves continuously</p>
                                    <div class="gradient-preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 feature-card">
                                <div class="card-body text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-sparkles fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">Glassmorphism</h5>
                                    <p class="card-text">Modern glass effect with backdrop blur and transparency</p>
                                    <div class="glass-preview"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 feature-card">
                                <div class="card-body text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-magic fa-3x"></i>
                                    </div>
                                    <h5 class="card-title">Hover Effects</h5>
                                    <p class="card-text">Incredible hover animations with scale, rotate, and glow effects</p>
                                    <div class="hover-preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Animation Showcase -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h4 class="mb-4">🎭 Animation Showcase</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="animation-card">
                                        <div class="animation-icon floating">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <h6>Floating Animation</h6>
                                        <p>Continuous floating motion</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="animation-card">
                                        <div class="animation-icon pulse">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h6>Pulse Effect</h6>
                                        <p>Rhythmic pulsing animation</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="animation-card">
                                        <div class="animation-icon rotate">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <h6>Rotation</h6>
                                        <p>Smooth continuous rotation</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="animation-card">
                                        <div class="animation-icon bounce">
                                            <i class="fas fa-basketball-ball"></i>
                                        </div>
                                        <h6>Bounce Effect</h6>
                                        <p>Energetic bouncing motion</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Color Palette -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h4 class="mb-4">🎨 Amazing Color Palette</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="color-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <span>Primary</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="color-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                        <span>Secondary</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="color-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        <span>Accent</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="color-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                        <span>Success</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="color-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                        <span>Warning</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="color-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                        <span>Light</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Elements -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h4 class="mb-4">⚡ Interactive Elements</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="interactive-demo">
                                        <h6>Search Functionality</h6>
                                        <div class="search-demo">
                                            <input type="text" placeholder="Type to see the amazing search effect..." class="form-control">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="interactive-demo">
                                        <h6>Hover Effects</h6>
                                        <div class="hover-demo">
                                            <button class="btn btn-primary amazing-btn">Hover Me!</button>
                                            <button class="btn btn-success amazing-btn">Amazing!</button>
                                            <button class="btn btn-warning amazing-btn">Wow!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <h4 class="mb-4">📊 Amazing Statistics</h4>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-code"></i>
                                        </div>
                                        <div class="stat-number">500+</div>
                                        <div class="stat-label">Lines of CSS</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-magic"></i>
                                        </div>
                                        <div class="stat-number">20+</div>
                                        <div class="stat-label">Animations</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-palette"></i>
                                        </div>
                                        <div class="stat-number">15+</div>
                                        <div class="stat-label">Gradients</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-rocket"></i>
                                        </div>
                                        <div class="stat-number">60fps</div>
                                        <div class="stat-label">Performance</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* AMAZING DEMO STYLES */
.feature-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.feature-icon {
    color: #667eea;
    margin-bottom: 20px;
}

.gradient-preview {
    width: 100%;
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
    background-size: 400% 400%;
    animation: gradientShift 3s ease infinite;
    border-radius: 10px;
    margin-top: 10px;
}

.glass-preview {
    width: 100%;
    height: 20px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 10px;
    margin-top: 10px;
}

.hover-preview {
    width: 100%;
    height: 20px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 10px;
    margin-top: 10px;
    transition: all 0.3s ease;
}

.hover-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* ANIMATION CARDS */
.animation-card {
    text-align: center;
    padding: 30px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.animation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.animation-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 30px;
}

.floating {
    animation: float 3s ease-in-out infinite;
}

.pulse {
    animation: pulse 2s ease-in-out infinite;
}

.rotate {
    animation: rotate 3s linear infinite;
}

.bounce {
    animation: bounce 2s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

/* COLOR CARDS */
.color-card {
    height: 100px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.color-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* INTERACTIVE DEMOS */
.interactive-demo {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.search-demo {
    position: relative;
}

.search-demo input {
    padding: 15px 50px 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.search-demo input:focus {
    border-color: #667eea;
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}

.search-demo i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
}

.hover-demo {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.amazing-btn {
    border-radius: 25px;
    padding: 12px 25px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border: none;
    position: relative;
    overflow: hidden;
}

.amazing-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s ease;
}

.amazing-btn:hover::before {
    left: 100%;
}

.amazing-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* STAT CARDS */
.stat-card {
    background: white;
    padding: 40px 20px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 30px;
}

.stat-number {
    font-size: 36px;
    font-weight: 800;
    color: #333;
    margin-bottom: 10px;
}

.stat-label {
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* HERO SECTION */
.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
    
    .hover-demo {
        justify-content: center;
    }
    
    .animation-card {
        margin-bottom: 20px;
    }
}
</style>
@endsection
