

<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userName = htmlspecialchars(
    $_SESSION['user_name'] ?? 'User',
    ENT_QUOTES,
    'UTF-8'
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quick Buy Online Shop - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #14213d;
            --ink-soft: #2b3a5c;
            --orange: #ff6b2c;
            --orange-dark: #e5551a;
            --orange-tint: #fff0e8;
            --teal: #1f9d82;
            --teal-tint: #e4f5f0;
            --bg: #f4f6fa;
            --card: #ffffff;
            --line: #e3e8f0;
            --text: #1d2740;
            --muted: #6b7690;
            --radius: 14px;
            --shadow: 0 2px 10px rgba(20, 33, 61, .06);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
        }

        :focus-visible {
            outline: 3px solid var(--orange);
            outline-offset: 2px;
        }

        /* ---------- Header ---------- */
        .header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--ink);
            color: #fff;
            box-shadow: 0 2px 12px rgba(20, 33, 61, .25);
        }

        .header-top {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 12px 20px;
        }

        .menu-btn {
            display: none;
            background: transparent;
            color: #fff;
            font-size: 24px;
            line-height: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 22px;
            letter-spacing: -.3px;
        }

        .logo-mark {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--orange);
            display: grid;
            place-items: center;
            font-size: 20px;
            transform: rotate(-6deg);
        }

        .logo span em {
            font-style: normal;
            color: var(--orange);
        }

        .search {
            flex: 1;
            display: flex;
            max-width: 640px;
        }

        .search input {
            flex: 1;
            padding: 11px 16px;
            border: none;
            border-radius: 10px 0 0 10px;
            font: inherit;
            font-size: 14px;
            color: var(--text);
            background: #fff;
        }

        .search input:focus-visible {
            outline-offset: -3px;
        }

        .search button {
            padding: 0 20px;
            background: var(--orange);
            color: #fff;
            font-weight: 700;
            border-radius: 0 10px 10px 0;
            font-size: 14px;
            transition: background .2s;
        }

        .search button:hover {
            background: var(--orange-dark);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .icon-btn {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .08);
            color: #fff;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: background .2s;
        }

        .icon-btn:hover {
            background: rgba(255, 255, 255, .18);
        }

        .badge {
            background: var(--orange);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            display: inline-grid;
            place-items: center;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--teal);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 14px;
        }

        .header-nav {
            background: var(--ink-soft);
        }

        .header-nav ul {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            list-style: none;
            display: flex;
            gap: 4px;
            overflow-x: auto;
        }

        .header-nav a {
            display: block;
            padding: 10px 14px;
            font-size: 13.5px;
            font-weight: 500;
            color: #cfd6e6;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
        }

        .header-nav a:hover,
        .header-nav a.active {
            color: #fff;
            border-bottom-color: var(--orange);
        }

        /* ---------- Layout ---------- */
        .layout {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px;
            display: grid;
            grid-template-columns: 230px minmax(0, 1fr) 270px;
            gap: 24px;
            align-items: start;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
        }

        /* ---------- Left sidebar ---------- */
        .sidebar {
            padding: 16px;
            position: sticky;
            top: 130px;
        }

        .sidebar h3 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        .cat-list {
            list-style: none;
            display: grid;
            gap: 4px;
        }

        .cat-list button {
            width: 100%;
            text-align: left;
            background: transparent;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .15s;
        }

        .cat-list button:hover {
            background: var(--bg);
        }

        .cat-list button.active {
            background: var(--orange-tint);
            color: var(--orange-dark);
            font-weight: 700;
        }

        .cat-list .ico {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .side-divider {
            height: 1px;
            background: var(--line);
            margin: 16px 0;
        }

        .help-box {
            background: var(--teal-tint);
            border-radius: 12px;
            padding: 14px;
            font-size: 13px;
            color: #14614f;
        }

        .help-box strong {
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
        }

        /* ---------- Main ---------- */
        main {
            display: grid;
            gap: 24px;
            min-width: 0;
        }

        .welcome h1 {
            font-size: 26px;
            letter-spacing: -.4px;
        }

        .welcome p {
            color: var(--muted);
            font-size: 14.5px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .stat {
            padding: 18px;
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .stat-ico {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat:nth-child(1) .stat-ico {
            background: var(--orange-tint);
        }

        .stat:nth-child(2) .stat-ico {
            background: #fde8ee;
        }

        .stat:nth-child(3) .stat-ico {
            background: var(--teal-tint);
        }

        .stat:nth-child(4) .stat-ico {
            background: #e8ecfa;
        }

        .stat b {
            display: block;
            font-size: 22px;
            line-height: 1.1;
        }

        .stat small {
            color: var(--muted);
            font-size: 13px;
        }

        /* Hero */
        .hero {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius);
            background: linear-gradient(120deg, var(--ink) 0%, var(--ink-soft) 100%);
            color: #fff;
            padding: 32px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .hero::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -80px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: var(--orange);
            opacity: .18;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            max-width: 420px;
        }

        .hero-tag {
            display: inline-block;
            background: var(--orange);
            font-size: 12.5px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .hero h2 {
            font-size: 30px;
            line-height: 1.15;
            letter-spacing: -.5px;
            margin-bottom: 8px;
        }

        .hero p {
            color: #c9d2e6;
            font-size: 14.5px;
            margin-bottom: 18px;
        }

        .btn {
            display: inline-block;
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            background: var(--orange);
            color: #fff;
            transition: background .2s, transform .15s;
        }

        .btn:hover {
            background: var(--orange-dark);
        }

        .btn:active {
            transform: scale(.97);
        }

        .countdown {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 10px;
        }

        .time-box {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 12px;
            padding: 12px 8px;
            min-width: 66px;
            text-align: center;
        }

        .time-box b {
            display: block;
            font-size: 26px;
            font-variant-numeric: tabular-nums;
        }

        .time-box span {
            font-size: 12px;
            color: #c9d2e6;
        }

        /* Section header */
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .section-head h2 {
            font-size: 19px;
            letter-spacing: -.2px;
        }

        .sort {
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: #fff;
            font: inherit;
            font-size: 13.5px;
            color: var(--text);
        }

        /* Products */
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 16px;
        }

        .product {
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s;
        }

        .product:hover {
            box-shadow: 0 8px 24px rgba(20, 33, 61, .12);
        }

        .p-img {
            height: 140px;
            display: grid;
            place-items: center;
            font-size: 62px;
            position: relative;
        }

        .p-off {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--ink);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 8px;
        }

        .p-body {
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }

        .p-cat {
            font-size: 12.5px;
            color: var(--muted);
        }

        .p-name {
            font-weight: 700;
            font-size: 15px;
            line-height: 1.3;
        }

        .p-rating {
            font-size: 12.5px;
            color: #c58a00;
        }

        .p-price {
            margin-top: auto;
            padding-top: 8px;
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .p-price b {
            font-size: 18px;
        }

        .p-price s {
            color: var(--muted);
            font-size: 13px;
        }

        .add-btn {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            background: var(--ink);
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            transition: background .2s;
        }

        .add-btn:hover {
            background: var(--orange);
        }

        .add-btn.added {
            background: var(--teal);
        }

        .empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px 10px;
            color: var(--muted);
        }

        /* Orders */
        .orders {
            padding: 20px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 520px;
        }

        th {
            text-align: left;
            font-weight: 600;
            color: var(--muted);
            font-size: 13px;
            padding: 8px 10px;
            border-bottom: 1px solid var(--line);
        }

        td {
            padding: 13px 10px;
            border-bottom: 1px solid var(--line);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status {
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 600;
            white-space: nowrap;
        }

        .st-delivered {
            background: var(--teal-tint);
            color: #14735d;
        }

        .st-shipped {
            background: #e8ecfa;
            color: #34499c;
        }

        .st-processing {
            background: var(--orange-tint);
            color: var(--orange-dark);
        }

        /* ---------- Right ads ---------- */
        .ads {
            display: grid;
            gap: 18px;
            position: sticky;
            top: 130px;
        }

        .ad-label {
            font-size: 11.5px;
            color: var(--muted);
            text-align: right;
            margin-bottom: 4px;
        }

        .ad {
            border-radius: var(--radius);
            padding: 22px 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            display: block;
            transition: transform .2s;
        }

        .ad:hover {
            transform: translateY(-3px);
        }

        .ad h4 {
            font-size: 20px;
            line-height: 1.2;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .ad p {
            font-size: 13.5px;
            opacity: .92;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .ad .ad-emoji {
            position: absolute;
            right: 8px;
            bottom: -6px;
            font-size: 76px;
            opacity: .3;
        }

        .ad .ad-btn {
            display: inline-block;
            background: #fff;
            color: var(--ink);
            font-weight: 700;
            font-size: 13px;
            padding: 7px 14px;
            border-radius: 8px;
            position: relative;
            z-index: 1;
        }

        .ad-1 {
            background: linear-gradient(135deg, #ff6b2c, #ff9a3c);
        }

        .ad-2 {
            background: linear-gradient(135deg, #1f9d82, #2fc2a2);
        }

        .ad-3 {
            background: linear-gradient(135deg, #14213d, #3a4f82);
        }

        /* ---------- Footer ---------- */
        .footer {
            background: var(--ink);
            color: #c9d2e6;
            margin-top: 30px;
        }

        .footer-grid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px 24px;
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1.3fr;
            gap: 32px;
        }

        .footer h4 {
            color: #fff;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .footer ul {
            list-style: none;
            display: grid;
            gap: 8px;
            font-size: 14px;
        }

        .footer a:hover {
            color: var(--orange);
        }

        .footer p {
            font-size: 14px;
            margin: 12px 0;
            max-width: 300px;
        }

        .subscribe {
            display: flex;
            margin-top: 4px;
        }

        .subscribe input {
            flex: 1;
            min-width: 0;
            padding: 10px 12px;
            border: none;
            border-radius: 10px 0 0 10px;
            font: inherit;
            font-size: 14px;
        }

        .subscribe button {
            padding: 0 16px;
            background: var(--orange);
            color: #fff;
            font-weight: 700;
            border-radius: 0 10px 10px 0;
            font-size: 14px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .12);
            text-align: center;
            padding: 16px 20px;
            font-size: 13px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translate(-50%, 30px);
            opacity: 0;
            background: var(--ink);
            color: #fff;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .25);
            transition: all .3s;
            pointer-events: none;
            z-index: 100;
        }

        .toast.show {
            opacity: 1;
            transform: translate(-50%, 0);
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 1150px) {
            .layout {
                grid-template-columns: 210px minmax(0, 1fr);
            }

            .ads {
                grid-column: 1 / -1;
                position: static;
                grid-template-columns: repeat(3, 1fr);
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 800px) {
            .menu-btn {
                display: block;
            }

            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
                position: static;
            }

            .sidebar.open {
                display: block;
            }

            .ads {
                grid-template-columns: 1fr;
            }

            .hero {
                flex-direction: column;
                align-items: flex-start;
                padding: 26px 22px;
            }

            .icon-btn .label {
                display: none;
            }

            .header-top {
                flex-wrap: wrap;
            }

            .search {
                order: 5;
                flex-basis: 100%;
                max-width: none;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .time-box {
                min-width: 58px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <header class="header">
        <div class="header-top">
            <button class="menu-btn" id="menuBtn" aria-label="Open categories">&#9776;</button>
            <a href="#" class="logo" aria-label="Quick Buy home">
                <div class="logo-mark">&#9889;</div>
                <span>Quick<em>Buy</em></span>
            </a>

            <form class="search" id="searchForm" role="search">
                <input type="search" id="searchInput" placeholder="Search products, brands and categories" aria-label="Search products">
                <button type="submit">Search</button>
            </form>

            <div class="header-actions">
                <button class="icon-btn" aria-label="Wishlist">&#9825; <span class="label">Wishlist</span></button>
                <button class="icon-btn" aria-label="Cart">&#128722; <span class="label">Cart</span> <span class="badge" id="cartCount">0</span></button>
                <div class="avatar">
    <?php
    $nameParts = preg_split('/\s+/', trim($_SESSION['user_name'] ?? 'User'));

    $initials = strtoupper(
        substr($nameParts[0], 0, 1) .
        (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    );

    echo htmlspecialchars($initials, ENT_QUOTES, 'UTF-8');
    ?>
</div>
            </div>
        </div>
        <nav class="header-nav" aria-label="Main">
            <ul>
                <li><a href="#" class="active">Dashboard</a></li>
                <li><a href="#products">Deals</a></li>
                <li><a href="#products">New arrivals</a></li>
                <li><a href="#orders">My orders</a></li>
                <li><a href="#">Gift cards</a></li>
                <li><a href="#">Sell on Quick Buy</a></li>
                <li><a href="#">Support</a></li>
            </ul>
        </nav>
    </header>

    <!-- ================= BODY ================= -->
    <div class="layout">

        <!-- Left sidebar: categories -->
        <aside class="card sidebar" id="sidebar">
            <h3>Shop by category</h3>
            <ul class="cat-list" id="catList">
                <li><button class="active" data-cat="all"><span class="ico">&#127968;</span>All products</button></li>
                <li><button data-cat="Electronics"><span class="ico">&#128241;</span>Electronics</button></li>
                <li><button data-cat="Fashion"><span class="ico">&#128085;</span>Fashion</button></li>
                <li><button data-cat="Home"><span class="ico">&#128715;</span>Home &amp; living</button></li>
                <li><button data-cat="Beauty"><span class="ico">&#128132;</span>Beauty</button></li>
                <li><button data-cat="Sports"><span class="ico">&#9917;</span>Sports</button></li>
            </ul>
            <div class="side-divider"></div>
            <div class="help-box">
                <strong>Need help?</strong>
                Our support team replies in under 5 minutes, every day.
            </div>
        </aside>

        <!-- Main content -->
        <main>
            <section class="welcome">
                <h1>Welcome back, <?= $userName ?></h1>
                <p>Here is what is happening with your orders and today's best deals.</p>
            </section>

            <section class="stats" aria-label="Account summary">
                <div class="card stat">
                    <div class="stat-ico">&#128230;</div>
                    <div><b>12</b><small>Total orders</small></div>
                </div>
                <div class="card stat">
                    <div class="stat-ico">&#10084;&#65039;</div>
                    <div><b>8</b><small>Wishlist items</small></div>
                </div>
                <div class="card stat">
                    <div class="stat-ico">&#128176;</div>
                    <div><b>$146.50</b><small>Wallet balance</small></div>
                </div>
                <div class="card stat">
                    <div class="stat-ico">&#11088;</div>
                    <div><b>2,430</b><small>Reward points</small></div>
                </div>
            </section>

            <section class="hero" aria-label="Flash sale">
                <div class="hero-copy">
                    <span class="hero-tag">Flash sale</span>
                    <h2>Up to 50% off on top electronics</h2>
                    <p>Grab headphones, watches and more before the timer runs out. Free delivery on orders over $50.</p>
                    <a href="#products" class="btn">Shop the sale</a>
                </div>
                <div class="countdown" aria-label="Time left">
                    <div class="time-box"><b id="hh">00</b><span>Hours</span></div>
                    <div class="time-box"><b id="mm">00</b><span>Minutes</span></div>
                    <div class="time-box"><b id="ss">00</b><span>Seconds</span></div>
                </div>
            </section>

            <section id="products">
                <div class="section-head">
                    <h2 id="productTitle">Recommended for you</h2>
                    <select class="sort" id="sortSelect" aria-label="Sort products">
                        <option value="default">Sort: Featured</option>
                        <option value="low">Price: low to high</option>
                        <option value="high">Price: high to low</option>
                        <option value="rating">Top rated</option>
                    </select>
                </div>
                <div class="products" id="productGrid"></div>
            </section>

            <section class="card orders" id="orders">
                <div class="section-head">
                    <h2>Recent orders</h2>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Item</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#QB-10482</td>
                                <td>Wireless headphones</td>
                                <td>10 Oct 2026</td>
                                <td>$59.00</td>
                                <td><span class="status st-processing">Processing</span></td>
                            </tr>
                            <tr>
                                <td>#QB-10455</td>
                                <td>Running sneakers</td>
                                <td>06 Oct 2026</td>
                                <td>$74.50</td>
                                <td><span class="status st-shipped">Shipped</span></td>
                            </tr>
                            <tr>
                                <td>#QB-10391</td>
                                <td>Smart LED lamp</td>
                                <td>29 Sep 2026</td>
                                <td>$24.99</td>
                                <td><span class="status st-delivered">Delivered</span></td>
                            </tr>
                            <tr>
                                <td>#QB-10347</td>
                                <td>Daily skincare set</td>
                                <td>21 Sep 2026</td>
                                <td>$38.20</td>
                                <td><span class="status st-delivered">Delivered</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <!-- Right side: advertisements -->
        <aside class="ads" aria-label="Advertisements">
            <div>
                <div class="ad-label">Sponsored</div>
                <a href="#" class="ad ad-1">
                    <h4>Free delivery weekend</h4>
                    <p>No minimum spend on all orders until Sunday night.</p>
                    <span class="ad-btn">Get started</span>
                    <span class="ad-emoji">&#128666;</span>
                </a>
            </div>
            <div>
                <div class="ad-label">Sponsored</div>
                <a href="#" class="ad ad-2">
                    <h4>Earn double points</h4>
                    <p>Pay with your Quick Buy wallet and collect 2x rewards.</p>
                    <span class="ad-btn">Join now</span>
                    <span class="ad-emoji">&#127873;</span>
                </a>
            </div>
            <div>
                <div class="ad-label">Sponsored</div>
                <a href="#" class="ad ad-3">
                    <h4>Back to work sale</h4>
                    <p>Laptops, bags and desk gear from $19.</p>
                    <span class="ad-btn">See offers</span>
                    <span class="ad-emoji">&#128187;</span>
                </a>
            </div>
        </aside>
    </div>

    <!-- ================= FOOTER ================= -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <a href="#" class="logo" style="color:#fff">
                    <div class="logo-mark">&#9889;</div><span>Quick<em>Buy</em></span>
                </a>
                <p>Fast, safe and simple online shopping. Everything you need, delivered to your door.</p>
            </div>
            <div>
                <h4>Shop</h4>
                <ul>
                    <li><a href="#">Electronics</a></li>
                    <li><a href="#">Fashion</a></li>
                    <li><a href="#">Home &amp; living</a></li>
                    <li><a href="#">Beauty</a></li>
                </ul>
            </div>
            <div>
                <h4>Help</h4>
                <ul>
                    <li><a href="#">Track my order</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Payment options</a></li>
                    <li><a href="#">Contact us</a></li>
                </ul>
            </div>
            <div>
                <h4>Get deals in your inbox</h4>
                <form class="subscribe" id="subscribeForm">
                    <input type="email" placeholder="Your email" aria-label="Email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">&copy; 2026 Quick Buy Online Shop. All rights reserved.</div>
    </footer>

    <div class="toast" id="toast" role="status" aria-live="polite"></div>

    <script>
        // ---------- Product data ----------
        const products = [{
                id: 1,
                name: "Wireless headphones",
                cat: "Electronics",
                price: 59,
                old: 89,
                rating: 4.7,
                emoji: "🎧",
                bg: "#fff0e8",
                off: "-34%"
            },
            {
                id: 2,
                name: "Smart watch Series 5",
                cat: "Electronics",
                price: 119,
                old: 160,
                rating: 4.5,
                emoji: "⌚",
                bg: "#e8ecfa",
                off: "-26%"
            },
            {
                id: 3,
                name: "Running sneakers",
                cat: "Sports",
                price: 74.5,
                old: 99,
                rating: 4.6,
                emoji: "👟",
                bg: "#e4f5f0",
                off: "-25%"
            },
            {
                id: 4,
                name: "Classic denim jacket",
                cat: "Fashion",
                price: 48,
                old: 70,
                rating: 4.3,
                emoji: "🧥",
                bg: "#e8ecfa",
                off: "-31%"
            },
            {
                id: 5,
                name: "Smart LED lamp",
                cat: "Home",
                price: 24.99,
                old: 35,
                rating: 4.4,
                emoji: "💡",
                bg: "#fff6dc",
                off: "-29%"
            },
            {
                id: 6,
                name: "Daily skincare set",
                cat: "Beauty",
                price: 38.2,
                old: 52,
                rating: 4.8,
                emoji: "🧴",
                bg: "#fde8ee",
                off: "-27%"
            },
            {
                id: 7,
                name: "Yoga mat pro",
                cat: "Sports",
                price: 22,
                old: 30,
                rating: 4.2,
                emoji: "🧘",
                bg: "#e4f5f0",
                off: "-27%"
            },
            {
                id: 8,
                name: "Bluetooth speaker",
                cat: "Electronics",
                price: 35,
                old: 55,
                rating: 4.6,
                emoji: "🔊",
                bg: "#fff0e8",
                off: "-36%"
            },
            {
                id: 9,
                name: "Leather backpack",
                cat: "Fashion",
                price: 64,
                old: 85,
                rating: 4.5,
                emoji: "🎒",
                bg: "#fff6dc",
                off: "-25%"
            },
            {
                id: 10,
                name: "Cozy throw blanket",
                cat: "Home",
                price: 29,
                old: 40,
                rating: 4.7,
                emoji: "🛋️",
                bg: "#fde8ee",
                off: "-28%"
            },
            {
                id: 11,
                name: "Perfume Bloom 50ml",
                cat: "Beauty",
                price: 45,
                old: 60,
                rating: 4.4,
                emoji: "🌸",
                bg: "#fde8ee",
                off: "-25%"
            },
            {
                id: 12,
                name: "Football size 5",
                cat: "Sports",
                price: 18,
                old: 25,
                rating: 4.1,
                emoji: "⚽",
                bg: "#e4f5f0",
                off: "-28%"
            }
        ];

        let activeCat = "all";
        let searchTerm = "";
        let sortBy = "default";
        let cart = 0;

        const grid = document.getElementById("productGrid");
        const cartCount = document.getElementById("cartCount");
        const toast = document.getElementById("toast");

        // ---------- Render products ----------
        function renderProducts() {
            let list = products.filter(p =>
                (activeCat === "all" || p.cat === activeCat) &&
                (p.name + " " + p.cat).toLowerCase().includes(searchTerm)
            );

            if (sortBy === "low") list.sort((a, b) => a.price - b.price);
            if (sortBy === "high") list.sort((a, b) => b.price - a.price);
            if (sortBy === "rating") list.sort((a, b) => b.rating - a.rating);

            if (!list.length) {
                grid.innerHTML = '<p class="empty">No products match your search. Try a different word or category.</p>';
                return;
            }

            grid.innerHTML = list.map(p => `
      <article class="card product">
        <div class="p-img" style="background:${p.bg}">
          <span class="p-off">${p.off}</span>
          <span aria-hidden="true">${p.emoji}</span>
        </div>
        <div class="p-body">
          <span class="p-cat">${p.cat}</span>
          <h3 class="p-name">${p.name}</h3>
          <span class="p-rating">★ ${p.rating.toFixed(1)}</span>
          <div class="p-price"><b>$${p.price.toFixed(2)}</b><s>$${p.old.toFixed(2)}</s></div>
          <button class="add-btn" data-id="${p.id}">Add to cart</button>
        </div>
      </article>
    `).join("");
        }

        // ---------- Add to cart ----------
        grid.addEventListener("click", e => {
            const btn = e.target.closest(".add-btn");
            if (!btn) return;
            cart++;
            cartCount.textContent = cart;
            const item = products.find(p => p.id === Number(btn.dataset.id));
            btn.textContent = "Added to cart";
            btn.classList.add("added");
            setTimeout(() => {
                btn.textContent = "Add to cart";
                btn.classList.remove("added");
            }, 1500);
            showToast(item.name + " added to your cart");
        });

        // ---------- Category filter ----------
        document.getElementById("catList").addEventListener("click", e => {
            const btn = e.target.closest("button");
            if (!btn) return;
            document.querySelectorAll("#catList button").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            activeCat = btn.dataset.cat;
            document.getElementById("productTitle").textContent =
                activeCat === "all" ? "Recommended for you" : btn.textContent.trim();
            renderProducts();
            document.getElementById("sidebar").classList.remove("open");
        });

        // ---------- Search and sort ----------
        document.getElementById("searchForm").addEventListener("submit", e => {
            e.preventDefault();
            searchTerm = document.getElementById("searchInput").value.trim().toLowerCase();
            renderProducts();
            document.getElementById("products").scrollIntoView();
        });
        document.getElementById("searchInput").addEventListener("input", e => {
            searchTerm = e.target.value.trim().toLowerCase();
            renderProducts();
        });
        document.getElementById("sortSelect").addEventListener("change", e => {
            sortBy = e.target.value;
            renderProducts();
        });

        // ---------- Mobile menu ----------
        document.getElementById("menuBtn").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("open");
        });

        // ---------- Flash sale countdown (plain seconds counter, no Date class) ----------
        const SALE_SECONDS = 8 * 60 * 60; // sale length: 8 hours, change as you like
        let secondsLeft = SALE_SECONDS;

        function tick() {
            const pad = n => String(n).padStart(2, "0");
            document.getElementById("hh").textContent = pad(Math.floor(secondsLeft / 3600));
            document.getElementById("mm").textContent = pad(Math.floor(secondsLeft / 60) % 60);
            document.getElementById("ss").textContent = pad(secondsLeft % 60);
            secondsLeft = secondsLeft > 0 ? secondsLeft - 1 : SALE_SECONDS; // restart when finished
        }
        tick();
        setInterval(tick, 1000);

        // ---------- Toast ----------
        let toastTimer;

        function showToast(msg) {
            toast.textContent = msg;
            toast.classList.add("show");
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.remove("show"), 2200);
        }

        // ---------- Newsletter ----------
        document.getElementById("subscribeForm").addEventListener("submit", e => {
            e.preventDefault();
            e.target.reset();
            showToast("Subscribed. Watch your inbox for deals.");
        });

        renderProducts();
    </script>
</body>

</html>