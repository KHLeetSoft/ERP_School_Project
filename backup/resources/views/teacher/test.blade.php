<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Module Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Teacher Module Test</h3>
                    </div>
                    <div class="card-body">
                        <h5>Teacher Login Module Created Successfully!</h5>
                        <p>All components have been created:</p>
                        <ul>
                            <li>✅ Teacher AuthController</li>
                            <li>✅ Teacher DashboardController</li>
                            <li>✅ Teacher Middleware</li>
                            <li>✅ Teacher Login View</li>
                            <li>✅ Teacher Layout</li>
                            <li>✅ Teacher Dashboard</li>
                            <li>✅ Teacher Profile</li>
                            <li>✅ Teacher Routes</li>
                        </ul>
                        
                        <h6>Test the Teacher Login:</h6>
                        <a href="{{ route('teacher.login') }}" class="btn btn-primary">Go to Teacher Login</a>
                        
                        <hr>
                        
                        <h6>Available Routes:</h6>
                        <ul>
                            <li><code>GET /teacher/login</code> - Teacher Login Form</li>
                            <li><code>POST /teacher/login</code> - Teacher Login Process</li>
                            <li><code>GET /teacher/dashboard</code> - Teacher Dashboard (Protected)</li>
                            <li><code>GET /teacher/profile</code> - Teacher Profile (Protected)</li>
                            <li><code>POST /teacher/logout</code> - Teacher Logout</li>
                        </ul>
                        
                        <h6>To test the login:</h6>
                        <ol>
                            <li>Create a user in the database with role = 'teacher'</li>
                            <li>Go to <a href="{{ route('teacher.login') }}">/teacher/login</a></li>
                            <li>Login with the teacher credentials</li>
                            <li>You'll be redirected to the teacher dashboard</li>
                        </ol>
                        
                        <h6>Sample SQL to create a test teacher:</h6>
                        <pre><code>INSERT INTO users (name, email, password, role, status, created_at, updated_at) 
VALUES ('Test Teacher', 'teacher@school.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj4J/8Qz8K2', 'teacher', 'active', NOW(), NOW());</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

