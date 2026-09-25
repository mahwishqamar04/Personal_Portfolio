<?php
$pageTitle = 'Mehwish Qamar | Data Analyst & Business Intelligence';
include 'includes/header.php';

// Load active projects from database for the Projects section
require_once __DIR__ . '/includes/db.php';
$projects = [];
$categories = [];
$screenshots = [];
$certificates = [];
$projectCount = 0;
$profileImage = 'assets/images/profile/profile-freelancer.jpg';

$conn = getDBConnection();

if ($conn) {
    // Load the current profile image from the profile table.
    $profileStmt = $conn->prepare("SELECT profile_image FROM profile ORDER BY id ASC LIMIT 1");

    if ($profileStmt) {
        $profileStmt->execute();
        $profileResult = $profileStmt->get_result();
        $profileRow = $profileResult->fetch_assoc();

        if ($profileRow && !empty($profileRow['profile_image'])) {
            $profileImage = $profileRow['profile_image'];
        }

        $profileStmt->close();
    }
}
if ($conn) {
    // Load certificates from database
    $certStmt = $conn->prepare("SELECT * FROM certificates ORDER BY id DESC");
    if ($certStmt) {
        $certStmt->execute();
        $certResult = $certStmt->get_result();
        while ($certRow = $certResult->fetch_assoc()) {
            $certificates[] = $certRow;
        }
        $certStmt->close();
    }
    // Get active projects ordered by display_order
    $stmt = $conn->prepare("SELECT * FROM projects WHERE status = 'active' ORDER BY display_order ASC, id ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    $stmt->close();

    $projectCount = count($projects);

    // Get distinct categories for filter buttons
    $catResult = $conn->query("SELECT DISTINCT category FROM projects WHERE status = 'active' AND category IS NOT NULL AND category != '' ORDER BY category ASC");
    if ($catResult) {
        while ($cat = $catResult->fetch_assoc()) {
            $categories[] = $cat['category'];
        }
        $catResult->close();
    }

    // Get screenshots for all active projects
    if (!empty($projects)) {
        $projectIds = array_column($projects, 'id');
        $placeholders = implode(',', array_fill(0, count($projectIds), '?'));
        $types = str_repeat('i', count($projectIds));
        $ssStmt = $conn->prepare("SELECT * FROM project_screenshots WHERE project_id IN ($placeholders) ORDER BY display_order ASC, id ASC");
        $ssStmt->bind_param($types, ...$projectIds);
        $ssStmt->execute();
        $ssResult = $ssStmt->get_result();
        while ($ss = $ssResult->fetch_assoc()) {
            $screenshots[$ss['project_id']][] = $ss;
        }
        $ssStmt->close();
    }
}

/**
 * Generate a URL-friendly filter slug from a category name.
 */
function categoryFilterSlug($category) {
    return strtolower(str_replace([' ', '&'], ['-', 'and'], trim($category)));
}
?>

<!-- ========== HERO SECTION ========== -->
<section id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h1 class="hero-name"><span class="highlight">Mehwish Qamar</span></h1>
                    <p class="hero-title">Data Analyst | Business Intelligence Expert | AI Web Developer</p>
                    <div class="hero-tags">
                        <span class="hero-tag">Data Analytics</span>
                        <span class="hero-tag">Power BI Dashboards</span>
                        <span class="hero-tag">AI Web Development</span>
                    </div>
                    <p class="hero-description">
                        I help businesses make sense of their data through clean dashboards, 
                        business intelligence reports, and practical web applications. Focused on 
                        Power BI, data visualization, and AI-powered web development.
                    </p>
                    <div class="hero-buttons">
                        <a href="#projects" class="btn btn-primary-custom">View My Projects</a>
                        <a href="#contact" class="btn btn-outline-custom">Let's Work Together</a>
                        <a href="https://www.linkedin.com/in/mehwish-qamar-133230375" target="_blank" rel="noopener noreferrer" class="btn btn-linkedin"><i class="bi bi-linkedin"></i> LinkedIn</a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <span class="hero-stat-number"><?php echo $projectCount; ?></span>
                            <span class="hero-stat-label">Projects</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-number">3</span>
                            <span class="hero-stat-label">Certifications</span>
                        </div>
                        <div class="hero-stat-item">
                            <span class="hero-stat-number">1</span>
                            <span class="hero-stat-label">Year at Shan Foods</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-image-wrapper">
                    <div class="hero-image-placeholder">
                        <img src="<?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8') ?>" alt="Mehwish Qamar - Data Analyst & AI Web Developer" class="hero-profile-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== ABOUT SECTION ========== -->
<section id="about" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">About Me</h2>
            <p class="section-subtitle">Getting to know my background and professional journey</p>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto">
                <div class="about-content">
                    <p>
    I am <strong>Mehwish Qamar</strong> - a <strong>Bachelor of Commerce</strong> graduate from the
    <strong>University of Karachi</strong>. My professional focus spans <strong>Data Analytics</strong>,
    <strong>Business Intelligence</strong>, and <strong>AI Web Development</strong>. I am building
    hands-on experience through projects involving <strong>Power BI dashboards</strong>,
    <strong>PHP and MySQL web applications</strong>, and <strong>AI-powered features</strong>.
</p>
                    <div class="about-info-grid">
                        <div class="about-info-item">
                            <i class="bi bi-mortarboard-fill"></i>
                            <div>
                                <span class="info-label">Education</span><br>
                                <span class="info-value">B.Com - University of Karachi</span>
                            </div>
                        </div>
                        <div class="about-info-item">
                            <i class="bi bi-briefcase-fill"></i>
                            <div>
                                <span class="info-label">Experience</span><br>
                                <span class="info-value">1 Year at Shan Foods (QC)</span>
                            </div>
                        </div>
                        <div class="about-info-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <span class="info-label">Focus Areas</span><br>
                                <span class="info-value">Data Analytics, Power BI, AI Web Development</span>
                            </div>
                        </div>
                        <div class="about-info-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>
                                <span class="info-label">Availability</span><br>
                                <span class="info-value">Open to Projects & Opportunities</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SKILLS SECTION ========== -->
<section id="skills" class="section section-alt">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Skills</h2>
            <p class="section-subtitle">Technologies and professional competencies I work with</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="skill-category">
                    <h4 class="skill-category-title">
                        <i class="bi bi-graph-up-arrow"></i> Data & Analytics
                    </h4>
                    <div>
                        <span class="skill-badge">Data Analytics</span>
                        <span class="skill-badge">Business Intelligence</span>
                        <span class="skill-badge">Power BI</span>
                        <span class="skill-badge">Data Visualization</span>
                        <span class="skill-badge">Dashboard Creation</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="skill-category">
                    <h4 class="skill-category-title">
                        <i class="bi bi-code-slash"></i> Web & AI
                    </h4>
                    <div>
                        <span class="skill-badge">AI Web Development</span>
                        <span class="skill-badge">HTML</span>
                        <span class="skill-badge">CSS</span>
                        <span class="skill-badge">Bootstrap</span>
                        <span class="skill-badge">JavaScript</span>
                        <span class="skill-badge">PHP</span>
                        <span class="skill-badge">MySQL</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="skill-category">
                    <h4 class="skill-category-title">
                        <i class="bi bi-people-fill"></i> Professional
                    </h4>
                    <div>
                        <span class="skill-badge">Freelancing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVICES SECTION ========== -->
<section id="services" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Services</h2>
            <p class="section-subtitle">Professional services I offer to clients and businesses</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-5 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <h4>Data Analysis & Dashboard Creation</h4>
                    <p>Creating clean, organized dashboards and data-driven reports that help turn raw data into clear, actionable insights for business needs.</p>
                    <div class="service-tech">
                        <span>Data Analytics</span>
                        <span>Data Visualization</span>
                        <span>Reporting</span>
                    </div>
                    <a href="#contact" class="btn btn-service-cta">Discuss a Project</a>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h4>AI Web Development</h4>
                    <p>Developing web applications with AI-focused features using PHP, JavaScript, and MySQL technologies with practical, intelligent functionality.</p>
                    <div class="service-tech">
                        <span>PHP</span>
                        <span>JavaScript</span>
                        <span>MySQL</span>
                        <span>AI Integration</span>
                    </div>
                    <a href="#contact" class="btn btn-service-cta">Discuss a Project</a>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <p style="color:var(--text-muted);margin-bottom:16px;">Need something specific? I'd be happy to discuss how I can help.</p>
            <a href="#contact" class="btn btn-primary-custom">Get In Touch</a>
        </div>
    </div>
</section>

<!-- ========== PROJECTS SECTION ========== -->
<section id="projects" class="section section-alt">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Projects</h2>
            <p class="section-subtitle">A showcase of my work across web development, AI, and data analytics</p>
        </div>

        <!-- Dynamic filter buttons from database categories -->
        <div class="project-filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <?php foreach ($categories as $cat): ?>
                <button class="filter-btn" data-filter="<?php echo htmlspecialchars(categoryFilterSlug($cat)); ?>">
                    <?php echo htmlspecialchars($cat); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Dynamic project cards from database -->
        <div class="row g-4">
            <?php if (empty($projects)): ?>
                <div class="col-12 text-center">
                    <p style="color: var(--text-muted); font-size: 1.05rem;">Projects coming soon. Check back later!</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project):
                    $catSlug = categoryFilterSlug($project['category']);
                    $techs = array_filter(array_map('trim', explode(',', $project['technologies'] ?? '')));
                    $projectScreenshots = $screenshots[$project['id']] ?? [];
                ?>
                <div class="col-lg-6 project-item" data-category="<?php echo htmlspecialchars($catSlug); ?>">
                    <div class="project-card">
                        <div class="project-img-wrapper">
                            <?php if (!empty($project['thumbnail'])): ?>
                                <img src="<?php echo htmlspecialchars($project['thumbnail']); ?>"
                                     alt="<?php echo htmlspecialchars($project['title']); ?>"
                                     onerror="this.style.display='none';this.parentNode.classList.add('project-img-placeholder')">
                            <?php else: ?>
                                <div class="project-img-placeholder"></div>
                            <?php endif; ?>
                            <div class="project-overlay">
                                <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#projectModal-<?php echo $project['id']; ?>">
                                    <i class="bi bi-eye"></i> View Details
                                </button>
                            </div>
                        </div>
                        <div class="project-body">
                            <span class="project-category"><?php echo htmlspecialchars($project['category']); ?></span>
                            <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                            <p><?php echo htmlspecialchars($project['short_description']); ?></p>
                            <?php if (!empty($techs)): ?>
                            <div class="project-tech">
                                <?php foreach ($techs as $tech): ?>
                                    <span><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <div class="project-links">
                                <?php if (!empty($project['live_demo_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['live_demo_url']); ?>"
                                       target="_blank" rel="noopener noreferrer"
                                       class="btn btn-sm btn-primary-custom">
                                        <i class="bi bi-box-arrow-up-right"></i> Live Demo
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($project['github_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_url']); ?>"
                                       target="_blank" rel="noopener noreferrer"
                                       class="btn btn-sm btn-outline-custom">
                                        <i class="bi bi-github"></i> GitHub
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-outline-custom" data-bs-toggle="modal" data-bs-target="#projectModal-<?php echo $project['id']; ?>">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== PROJECT DETAIL MODALS ========== -->
<?php foreach ($projects as $project):
    $techs = array_filter(array_map('trim', explode(',', $project['technologies'] ?? '')));
    $projectScreenshots = $screenshots[$project['id']] ?? [];
?>
<div class="modal fade" id="projectModal-<?php echo $project['id']; ?>" tabindex="-1" aria-labelledby="projectModalLabel-<?php echo $project['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectModalLabel-<?php echo $project['id']; ?>">
                    <?php echo htmlspecialchars($project['title']); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="project-category"><?php echo htmlspecialchars($project['category']); ?></span>

                <p class="project-modal-description">
                    <?php echo htmlspecialchars($project['detailed_description'] ?? $project['short_description'] ?? ''); ?>
                </p>

                <?php if (!empty($techs)): ?>
                <div class="project-tech mb-3">
                    <?php foreach ($techs as $tech): ?>
                        <span><?php echo htmlspecialchars($tech); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($projectScreenshots)): ?>
                <div class="project-screenshots-section">
                    <h6 class="project-modal-subtitle"><i class="bi bi-images"></i> Project Screenshots</h6>
                    <div id="projectCarousel-<?php echo $project['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php foreach ($projectScreenshots as $idx => $ss): ?>
                                <button type="button"
                                        data-bs-target="#projectCarousel-<?php echo $project['id']; ?>"
                                        data-bs-slide-to="<?php echo $idx; ?>"
                                        <?php echo $idx === 0 ? 'class="active" aria-current="true"' : ''; ?>
                                        aria-label="<?php echo htmlspecialchars($ss['caption'] ?? 'Screenshot ' . ($idx + 1)); ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php foreach ($projectScreenshots as $idx => $ss): ?>
                            <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($ss['image_path']); ?>"
                                     class="d-block w-100 project-screenshot-img"
                                     alt="<?php echo htmlspecialchars($ss['caption'] ?? 'Project screenshot'); ?>"
                                     onerror="this.parentElement.style.display='none'">
                                <?php if (!empty($ss['caption'])): ?>
                                <div class="carousel-caption">
                                    <p><?php echo htmlspecialchars($ss['caption']); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($projectScreenshots) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel-<?php echo $project['id']; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel-<?php echo $project['id']; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="project-modal-links mt-4">
                    <?php if (!empty($project['live_demo_url'])): ?>
                        <a href="<?php echo htmlspecialchars($project['live_demo_url']); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-primary-custom btn-sm me-2 mb-2">
                            <i class="bi bi-box-arrow-up-right"></i> Live Demo
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($project['github_url'])): ?>
                        <a href="<?php echo htmlspecialchars($project['github_url']); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-outline-custom btn-sm me-2 mb-2">
                            <i class="bi bi-github"></i> GitHub
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-custom btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- ========== EXPERIENCE SECTION ========== -->
<section id="experience" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Experience</h2>
            <p class="section-subtitle">My professional journey and continuous learning</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <span class="timeline-date">1 Year Professional Experience</span>
                            <h4>Quality Controller (QC)</h4>
                            <p class="company"><i class="bi bi-building"></i> Shan Foods</p>
                            <ul>
                                <li>Quality checking of products</li>
                                <li>Monitoring quality standards &amp; maintaining consistency</li>
                                <li>Attention to detail in assessments</li>
                                <li>Teamwork and professional responsibility</li>
                            </ul>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot timeline-dot-current"></div>
                        <div class="timeline-content">
                            <span class="timeline-date">Currently</span>
                            <h4>Learning & Practice</h4>
                            <p class="company"><i class="bi bi-book"></i> Self-Directed Skill Development</p>
                            <ul>
                                <li>Building Power BI dashboards, data reports, and AI web applications</li>
                                <li>Developing client-ready solutions through hands-on project practice</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== EDUCATION & CERTIFICATIONS SECTION ========== -->
<section id="certifications" class="section section-alt">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Education & Certifications</h2>
            <p class="section-subtitle">Academic background and professional certifications</p>
        </div>

        <!-- Education -->
        <h5 class="subsection-label"><i class="bi bi-mortarboard-fill"></i> Education</h5>
        <div class="row mb-5">
            <div class="col-lg-5 col-md-6 mx-auto">
                <div class="cert-card cert-card-education">
                    <div class="cert-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="cert-details">
                        <h5>Bachelor of Commerce (B.Com)</h5>
                        <p class="cert-issuer"><i class="bi bi-building"></i> University of Karachi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certifications -->
        <h5 class="subsection-label"><i class="bi bi-award-fill"></i> Certifications</h5>
        <div class="row g-4">
<?php if (!empty($certificates)): ?>
<?php foreach ($certificates as $certificate): ?>
            <div class="col-lg-4 col-md-6">
                <div class="cert-card">
                    <?php if (!empty($certificate['image'])): ?>
                        <div class="cert-image-wrapper">
                            <img src="<?= htmlspecialchars($certificate['image'], ENT_QUOTES, 'UTF-8') ?>"
                                 alt="<?= htmlspecialchars($certificate['title'], ENT_QUOTES, 'UTF-8') ?>"
                                 class="cert-image">
                        </div>
                    <?php else: ?>
                        <div class="cert-icon"><i class="bi bi-award-fill"></i></div>
                    <?php endif; ?>

                    <h5><?= htmlspecialchars($certificate['title'], ENT_QUOTES, 'UTF-8') ?></h5>

                    <?php if (!empty($certificate['organization'])): ?>
                        <p class="cert-issuer"><?= htmlspecialchars($certificate['organization'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>
            </div>
<?php endforeach; ?>
<?php else: ?>
            <div class="col-12">
                <p class="text-center">No certifications available yet.</p>
            </div>
<?php endif; ?>
        </div>        </div>
    </div>
</section>

<!-- Pre-Contact CTA -->
<div class="section">
<div class="container">
    <div class="pre-contact-cta">
        <h4>Ready to turn your data into decisions?</h4>
        <p>I'm available for freelance projects, dashboard creation, and web development. Let's discuss how I can help your business.</p>
        <a href="#contact" class="btn btn-primary-custom">Start a Conversation</a>
    </div>
</div>
</div>

<!-- ========== CONTACT SECTION ========== -->
<section id="contact" class="section section-alt">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">Have a project in mind? Let's work together to create something valuable.</p>
        </div>

        <!-- Contact Intro -->
        <div class="contact-intro text-center mb-5">
            <h4 class="contact-intro-name">Mehwish Qamar</h4>
            <p class="contact-intro-title">Data Analyst | Business Intelligence Expert | AI Web Developer</p>
        </div>

        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="contact-info-list">
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div class="contact-info-text">
                            <h5>Email</h5>
                            <p><a href="mailto:mahwishqamar4@gmail.com">mahwishqamar4@gmail.com</a></p>
                        </div>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="bi bi-linkedin"></i></div>
                        <div class="contact-info-text">
                            <h5>LinkedIn</h5>
                            <p><a href="https://www.linkedin.com/in/mehwish-qamar-133230375" target="_blank" rel="noopener noreferrer">View My Profile</a></p>
                        </div>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="bi bi-github"></i></div>
                        <div class="contact-info-text">
                            <h5>GitHub</h5>
                            <p><a href="https://github.com/mahwishqamar04" target="_blank" rel="noopener noreferrer">View My Repositories</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form">
                    <h5 class="contact-form-title">Send a Message</h5>
                    <form id="contactForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contactName" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="contactName" name="name" placeholder="Your full name" maxlength="100" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contactEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="contactEmail" name="email" placeholder="your@email.com" required>
                            </div>
                            <div class="col-12">
                                <label for="contactSubject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="contactSubject" name="subject" placeholder="What is this about?" maxlength="200" required>
                            </div>
                            <div class="col-12">
                                <label for="contactMessage" class="form-label">Message *</label>
                                <textarea class="form-control" id="contactMessage" name="message" placeholder="Your message..." maxlength="2000" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-submit"><i class="bi bi-send-fill"></i> Send Message</button>
                            </div>
                        </div>
                        <div class="form-message"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== AI ASSISTANT SECTION ========== -->
<section id="ai-assistant" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">AI Portfolio Assistant</h2>
            <p class="section-subtitle">Ask anything about my skills, projects, or services</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="ai-assistant-info">
                    <i class="bi bi-robot"></i>
                    <h4>Interactive AI Assistant</h4>
                    <p>Use the floating chat button in the bottom-right corner to interact with my AI Portfolio Assistant. It can answer questions about my skills, projects, services, experience, certifications, and more.</p>
                    <p style="font-size:0.85rem;color:var(--text-muted);">No API keys or external services required. Built with JavaScript and jQuery.</p>
                    <div class="ai-suggestions">
                        <span class="ai-suggestion-chip">What skills does Mehwish Qamar have?</span>
                        <span class="ai-suggestion-chip">Tell me about the Quetta Services Hub</span>
                        <span class="ai-suggestion-chip">What services does Mehwish offer?</span>
                        <span class="ai-suggestion-chip">What is Mehwish Qamar's education?</span>
                        <span class="ai-suggestion-chip">What is Mehwish Qamar's LinkedIn?</span>
                        <span class="ai-suggestion-chip">What are Mehwish Qamar's career goals?</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>














