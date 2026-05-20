<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Inventory System Cooperative') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            color-scheme: light;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background: #fff1f0;
            color: #111827;
        }

        body {
            font-family: inherit;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
            background: radial-gradient(circle at top left, rgba(48, 173, 240, 0.64), transparent 30%),
                        radial-gradient(circle at bottom right, rgba(82, 119, 252, 0.12), transparent 28%),
                        #f7f6f4;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-radius: 1.5rem;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 18px 50px rgba(17, 24, 39, 0.08);
            backdrop-filter: blur(18px);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        .navbar-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.15rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .navbar-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 110px;
        }

        .navbar-links a:hover,
        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary,
        .btn-primary:hover {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }

        .btn-secondary,
        .btn-secondary:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border-color: rgba(239, 68, 68, 0.25);
        }

        .hero {
            display: grid;
            gap: 2rem;
            padding: 3rem 2rem;
            border-radius: 2rem;
            background: linear-gradient(180deg, rgba(83, 49, 251, 0.95) 0%, rgba(255, 255, 255, 0.95) 100%);
            box-shadow: 0 28px 90px rgba(15, 23, 42, 0.08);
        }

        .hero-content {
            max-width: 720px;
            margin: 0 auto;
            text-align: center;
        }

        .hero h1 {
            margin: 0 0 1rem;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
        }

        .hero p {
            margin: 0;
            max-width: 720px;
            font-size: 1.05rem;
            color: #4b5563;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.75rem;
        }

        .section-title {
            margin: 0 0 1.5rem;
            font-size: 1.8rem;
            letter-spacing: -0.03em;
            text-align: center;
            color: #021636;
        }

        .services,
        .benefits,
        .cta {
            display: grid;
            gap: 1.75rem;
            padding: 2rem;
            border-radius: 1.75rem;
            background: linear-gradient(180deg, rgba(117, 91, 247, 0.95) 0%, rgba(255, 255, 255, 0.95) 100%);
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.06);
        }

        .services-grid {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .service-card,
        .benefit-item {
            background: #ffffff;
            border-radius: 1.25rem;
            padding: 1.5rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .service-icon,
        .benefit-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: grid;
            place-items: center;
            border-radius: 1rem;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .service-card h3,
        .benefit-item h4 {
            margin: 0 0 0.75rem;
            font-size: 1.05rem;
        }

        .service-card p,
        .benefit-item p {
            margin: 0;
            color: #4b5563;
            font-size: 0.98rem;
            line-height: 1.75;
        }

        .benefits-content {
            display: grid;
            gap: 1.5rem;
        }

        .benefits-list {
            display: grid;
            gap: 1rem;
        }

        .benefit-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: flex-start;
        }

        .cta-content {
            text-align: center;
        }

        .cta p {
            color: #4b5563;
            margin: 0 auto;
            max-width: 700px;
            font-size: 1rem;
        }

        .footer {
            text-align: center;
            padding: 1rem 0;
            color: #6b7280;
            font-size: 0.95rem;
        }

        @media (min-width: 760px) {
            .hero,
            .services,
            .benefits,
            .cta {
                padding: 3rem;
            }

            .navbar {
                padding: 1.25rem 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="container">
            @if (Route::has('login'))
                <nav class="navbar">
                    <div class="navbar-brand">Hello Visitor!</div>
                    <div class="navbar-links">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary">Log In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif

            <section class="hero">
                <div class="hero-content">
                    <h1>Welcome to Inventory System Cooperative</h1>
                    <p>Your complete solution for efficient inventory management — built for teams, stock control, and secure daily operations.</p>
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Get Started — Sign Up</a>
                            @endif
                            <a href="{{ route('login') }}" class="btn btn-secondary">Already a Member? Log In</a>
                        @endauth
                    </div>
                </div>
            </section>

            <section class="services">
                <h2 class="section-title">Our Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">📦</div>
                        <h3>Inventory Management</h3>
                        <p>Track, organize, and manage stock in real-time. Stay on top of pricing, quantities, and reorder needs.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">👥</div>
                        <h3>Team Collaboration</h3>
                        <p>Invite team members with role-based access. Assign tasks, monitor activity, and keep information secure.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">🖼️</div>
                        <h3>Product Imaging</h3>
                        <p>Upload and manage product images for fast identification and better stock control.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">💬</div>
                        <h3>Built-in Communication</h3>
                        <p>Add notes to inventory items and keep your team synchronized with clear updates.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">🔒</div>
                        <h3>Secure & Reliable</h3>
                        <p>Protect your data with secure authentication and reliable inventory storage.</p>
                    </div>
                </div>
            </section>

            <section class="benefits">
                <div class="benefits-content">
                    <h2 class="section-title">How We Help Your Business</h2>
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon">✓</div>
                            <div>
                                <h4>Reduce Operational Errors</h4>
                                <p>Minimize inventory discrepancies with accurate, real-time tracking.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">✓</div>
                            <div>
                                <h4>Save Time & Resources</h4>
                                <p>Automate inventory tasks and eliminate manual data entry.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">✓</div>
                            <div>
                                <h4>Improve Team Coordination</h4>
                                <p>Enable seamless collaboration with clear roles and permissions.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">✓</div>
                            <div>
                                <h4>Scale Your Operations</h4>
                                <p>Grow with flexible inventory management designed for businesses of every size.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">✓</div>
                            <div>
                                <h4>Increase Visibility</h4>
                                <p>Quickly see stock status, item movement, and inventory performance.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">✓</div>
                            <div>
                                <h4>Protect Your Data</h4>
                                <p>Secure access control keeps your inventory and team workflows safe.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cta">
                <div class="cta-content">
                    <h2 class="section-title">Ready to Transform Your Inventory Management?</h2>
                    <p>Join businesses already using Inventory System Cooperative to streamline operations, reduce waste, and improve stock accuracy.</p>
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Create Your Account Today</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </section>

            <footer class="footer">
                <p>&copy; {{ now()->year }} {{ config('app.name', 'Inventory System Cooperative') }}. All rights reserved.</p>
            </footer>
        </div>
    </div>
</body>
</html>
