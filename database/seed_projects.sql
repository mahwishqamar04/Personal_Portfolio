-- ============================================
-- Mehwish Qamar Portfolio - Seed Project Data
-- ============================================
-- HOW TO USE:
--   1. BACK UP your database first
--   2. Open phpMyAdmin, select portfolio_db
--   3. Go to SQL tab, paste this, click Go
--   4. Run ONCE. Running again duplicates the INSERT.
-- ============================================

-- 1. Quetta Services Hub (id=1)
UPDATE `projects` SET
    `slug` = 'quetta-services-hub',
    `short_description` = 'Home services booking platform built with Core PHP, MySQL, Bootstrap, JavaScript and responsive UI.',
    `detailed_description` = 'A comprehensive home services platform built with Core PHP and MySQL. Users can browse and explore services including plumbing, electrical repair, AC servicing, house cleaning, painting and carpentry. Features a MySQL-backed database, AI Service Advisor, booking functionality, and admin panel with CRUD operations.',
    `technologies` = 'Core PHP,MySQL,Bootstrap,JavaScript,HTML',
    `category` = 'Web Development',
    `thumbnail` = 'assets/images/projects/thumbnails/quetta-services-hub.png',
    `live_demo_url` = 'https://quettaserviceshub1.rf.gd',
    `github_url` = 'https://github.com/mahwishqamar04/quetta_serviceshub1',
    `status` = 'active',
    `display_order` = 1,
    `updated_at` = NOW()
WHERE `id` = 1;

-- 2. BizGuard AI (id=2)
UPDATE `projects` SET
    `slug` = 'bizguard-ai',
    `short_description` = 'AI web development project exploring practical business-oriented AI solutions.',
    `detailed_description` = 'An AI-focused web development project created to explore practical business-oriented AI solutions and demonstrate how intelligent features can address modern business challenges. Features business-oriented AI functionality, responsive frontend, PHP backend with database integration.',
    `technologies` = 'AI Web Development,PHP,MySQL,JavaScript,HTML,CSS',
    `category` = 'AI Web Development',
    `thumbnail` = 'assets/images/projects/thumbnails/bizguard-ai.png',
    `live_demo_url` = NULL,
    `github_url` = 'https://github.com/mahwishqamar04',
    `status` = 'active',
    `display_order` = 2,
    `updated_at` = NOW()
WHERE `id` = 2;

-- 3. Mil Gaya AI (id=3)
UPDATE `projects` SET
    `slug` = 'mil-gaya-ai',
    `short_description` = 'AI web development practice project.',
    `detailed_description` = 'An AI-focused web development project created as part of practical AI web development learning. Builds hands-on experience with intelligent web features and modern development workflows.',
    `technologies` = 'AI Web Development,PHP,MySQL,JavaScript,HTML,CSS',
    `category` = 'AI Web Development',
    `thumbnail` = 'assets/images/projects/thumbnails/mil-gaya-ai.png',
    `live_demo_url` = NULL,
    `github_url` = 'https://github.com/mahwishqamar04',
    `status` = 'active',
    `display_order` = 4,
    `updated_at` = NOW()
WHERE `id` = 3;

-- 4. Power BI Sales Dashboard (id=4)
UPDATE `projects` SET
    `slug` = 'power-bi-sales-dashboard',
    `short_description` = 'Interactive Power BI sales dashboard demonstrating data analytics and business intelligence skills.',
    `detailed_description` = 'An interactive Power BI sales dashboard for Data Analytics and Business Intelligence practice. Features KPIs, interactive visualizations, dynamic filters, drill-downs, sales performance analysis, and business insights reporting.',
    `technologies` = 'Power BI',
    `category` = 'Data Analytics',
    `thumbnail` = 'assets/images/projects/thumbnails/powerbi-dashboard.png',
    `live_demo_url` = NULL,
    `github_url` = NULL,
    `status` = 'active',
    `display_order` = 5,
    `updated_at` = NOW()
WHERE `id` = 4;

-- INSERT new project: BizGuard AI Winner
INSERT INTO `projects` (`slug`, `title`, `short_description`, `detailed_description`, `technologies`, `category`, `thumbnail`, `github_url`, `live_demo_url`, `status`, `display_order`, `created_at`, `updated_at`)
VALUES (
    'bizguard-ai-winner',
    'BizGuard AI Winner',
    'AI web development project / winning project.',
    'An award-winning AI web development project demonstrating excellence in building intelligent business solutions. Showcases advanced AI integration with modern web technologies, innovative business-oriented AI functionality, and a polished responsive UI.',
    'AI Web Development,PHP,MySQL,JavaScript,HTML,CSS',
    'AI Web Development',
    'assets/images/projects/thumbnails/bizguard-ai-winner.png',
    'https://github.com/mahwishqamar04',
    NULL,
    'active',
    3,
    NOW(),
    NOW()
);

-- INSERT screenshots for BizGuard AI (4 screenshots)
INSERT INTO `project_screenshots` (`project_id`, `image_path`, `caption`, `display_order`) VALUES
(2, 'assets/images/projects/screenshots/bizguard-ai-home.png', 'BizGuard AI - Home Page', 1),
(2, 'assets/images/projects/screenshots/bizguard-ai-dashboard.png', 'BizGuard AI - Dashboard', 2),
(2, 'assets/images/projects/screenshots/bizguard-ai-features.png', 'BizGuard AI - Features Page', 3),
(2, 'assets/images/projects/screenshots/bizguard-ai-about.png', 'BizGuard AI - About Page', 4);
