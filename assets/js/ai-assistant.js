/**
 * Mehwish Qamar Portfolio - AI Portfolio Assistant
 * Rule-based chatbot that answers questions about Mehwish Qamar's portfolio.
 * No external APIs or API keys used.
 */

(function($) {
    'use strict';

    var portfolioData = {
        greeting: {
            keywords: ['hello', 'hi', 'hey', 'good morning', 'good evening', 'salam', 'assalam'],
            response: "Hello! Welcome to Mehwish Qamar's portfolio. I'm the AI Portfolio Assistant. You can ask me about Mehwish's skills, projects, services, experience, education, certifications, or how to get in touch. What would you like to know?"
        },
        about: {
            keywords: ['who', 'about', 'tell me', 'introduce', 'introduction', 'background', 'mehwish', 'qamar'],
            response: "<strong>Mehwish Qamar</strong> is a Data Analyst, Business Intelligence Enthusiast, and AI Web Developer.\n\n" +
                "She holds a <strong>Bachelor of Commerce (B.Com)</strong> from the <strong>University of Karachi</strong> and has <strong>1 year of professional experience at Shan Foods</strong> as a Quality Controller.\n\n" +
                "Mehwish Qamar specializes in Data Analytics, Power BI dashboards, and AI-powered web development, and is actively building her expertise through hands-on projects."
        },
        skills: {
            keywords: ['skill', 'skills', 'know', 'expertise', 'technologies', 'tech stack', 'tools', 'what can'],
            response: "Mehwish Qamar has a diverse skill set spanning Data Analytics and AI Web Development:\n\n" +
                "<strong>Data & Analytics:</strong> Data Analytics, Business Intelligence, Power BI, Data Visualization, Dashboard Creation\n\n" +
                "<strong>Web & AI:</strong> AI Web Development, HTML, CSS, Bootstrap, JavaScript, jQuery, Core PHP, MySQL, XAMPP\n\n" +
                "<strong>Professional:</strong> Freelancing, Problem Solving, Communication, Teamwork, Attention to Detail"
        },
        dataanalytics: {
            keywords: ['data analytics', 'data analysis', 'data analyst'],
            response: "<strong>Data Analytics</strong> is one of Mehwish Qamar's core focus areas.\n\n" +
                "She works with data to extract meaningful insights, create structured reports, and build visualizations that support business decision making.\n\n" +
                "Her Data Analytics skills include: Power BI, Data Visualization, Dashboard Creation, Business Intelligence, and working with structured datasets.\n\n" +
                "She has applied these skills in projects like the <strong>Interactive Sales Dashboard</strong> and continues to practice through hands-on work with real datasets."
        },
        businessintelligence: {
            keywords: ['business intelligence', 'bi ', 'bi dashboards'],
            response: "<strong>Business Intelligence</strong> is a key area of Mehwish Qamar's professional focus.\n\n" +
                "She builds structured BI reports and interactive dashboards using <strong>Power BI</strong> to help organizations make data-driven decisions.\n\n" +
                "Her BI work includes creating KPIs, interactive visualizations, filters, drill-downs, and business insights reporting.\n\n" +
                "The <strong>Interactive Sales Dashboard</strong> is a practical example of her Business Intelligence work."
        },
        powerbi: {
            keywords: ['power bi', 'powerbi'],
            response: "<strong>Power BI</strong> is one of Mehwish Qamar's primary tools for Data Analytics and Business Intelligence.\n\n" +
                "She uses Power BI to create interactive dashboards with KPIs, dynamic filters, drill-downs, and business insights.\n\n" +
                "Her <strong>Interactive Sales Dashboard</strong> project demonstrates her Power BI capabilities — featuring sales performance analysis, interactive visualizations, and structured reporting."
        },
        aiwebdev: {
            keywords: ['ai web', 'ai web development', 'ai web developer', 'artificial intelligence'],
            response: "<strong>AI Web Development</strong> is one of Mehwish Qamar's core specializations.\n\n" +
                "She builds web applications with AI-focused features using technologies like <strong>PHP, JavaScript, MySQL, HTML, CSS, Bootstrap, and jQuery</strong>.\n\n" +
                "Her AI web development projects include <strong>Quetta Services Hub</strong> (with an AI Service Advisor), <strong>BizGuard AI</strong>, and <strong>MilGaya AI</strong>.\n\n" +
                "She is continuously developing her skills in this area through practical, hands-on projects."
        },
        projects: {
            keywords: ['project', 'projects', 'portfolio work', 'built', 'created', 'developed'],
            response: "Mehwish Qamar has worked on several notable projects:\n\n" +
                "1. <strong>Quetta Services Hub</strong> (Web Development) — A home services platform for plumbing, electrical repair, AC servicing, house cleaning, painting and carpentry — with an AI Service Advisor and MySQL database. (Core PHP, MySQL, HTML, Bootstrap, JavaScript)\n\n" +
                "2. <strong>BizGuard AI</strong> (AI Web Development) — An AI-focused project exploring practical business-oriented AI solutions. (AI Web Development, HTML, CSS, JavaScript, PHP, MySQL)\n\n" +
                "3. <strong>MilGaya AI</strong> (AI Web Development) — A practical AI web development learning project. (AI Web Development, HTML, CSS, JavaScript, PHP, MySQL)\n\n" +
                "4. <strong>Interactive Sales Dashboard</strong> (Data Analytics) — An interactive Power BI sales dashboard for BI practice. (Power BI)"
        },
        quetta: {
            keywords: ['quetta', 'services hub', 'quetta services'],
            response: "<strong>Quetta Services Hub</strong> is a home services platform built with Core PHP and MySQL. Users can browse and explore services including plumbing, electrical repair, AC servicing, house cleaning, painting and carpentry.\n\n" +
                "<strong>Key Features:</strong>\n" +
                "• Service browsing & exploration\n" +
                "• Booking functionality\n" +
                "• Admin panel with CRUD operations\n" +
                "• AI Service Advisor\n" +
                "• MySQL database integration\n\n" +
                "<strong>Technologies:</strong> Core PHP, MySQL, HTML, Bootstrap, JavaScript\n\n" +
                "<strong>Role:</strong> Full Development & Implementation\n\n" +
                "<strong>Outcome:</strong> A functional local services platform with AI assistance."
        },
        bizguard: {
            keywords: ['bizguard', 'bizguard ai'],
            response: "<strong>BizGuard AI</strong> is an AI-focused web development project created to explore practical business-oriented AI solutions and demonstrate how intelligent features can address modern business challenges.\n\n" +
                "<strong>Key Features:</strong>\n" +
                "• Business-oriented AI functionality\n" +
                "• Responsive frontend interface\n" +
                "• PHP backend with database integration\n" +
                "• Practical AI solution exploration\n\n" +
                "<strong>Technologies:</strong> AI Web Development, HTML, CSS, JavaScript, PHP, MySQL\n\n" +
                "<strong>Role:</strong> Developer & AI Integration\n\n" +
                "<strong>Outcome:</strong> Exploring practical AI applications for business use cases."
        },
        milgaya: {
            keywords: ['milgaya', 'milgaya ai'],
            response: "<strong>MilGaya AI</strong> is an AI-focused web development project created as part of practical AI web development learning — building hands-on experience with intelligent web features and modern development workflows.\n\n" +
                "<strong>Key Features:</strong>\n" +
                "• AI-powered web functionality\n" +
                "• Responsive web interface\n" +
                "• PHP backend architecture\n" +
                "• Database integration\n\n" +
                "<strong>Technologies:</strong> AI Web Development, HTML, CSS, JavaScript, PHP, MySQL\n\n" +
                "<strong>Role:</strong> Developer\n\n" +
                "<strong>Outcome:</strong> Hands-on AI web development learning project."
        },
        dashboard: {
            keywords: ['dashboard', 'sales dashboard', 'bi dashboard', 'interactive sales'],
            response: "The <strong>Interactive Sales Dashboard</strong> is an interactive Power BI sales dashboard developed for Data Analytics and Business Intelligence practice.\n\n" +
                "<strong>Key Features:</strong>\n" +
                "• KPIs & interactive visualizations\n" +
                "• Dynamic filters & drill-downs\n" +
                "• Sales performance analysis\n" +
                "• Business insights & reporting\n\n" +
                "<strong>Technology:</strong> Power BI\n\n" +
                "<strong>Role:</strong> Data Analyst & Dashboard Designer\n\n" +
                "<strong>Outcome:</strong> A practical BI dashboard for data-driven decision making."
        },
        services: {
            keywords: ['service', 'services', 'offer', 'provide', 'hire', 'what do you do'],
            response: "Mehwish Qamar offers two main professional services:\n\n" +
                "1. <strong>Data Analysis & Dashboard Creation</strong> — Creating clean, organized dashboards and data-driven reports that turn raw data into clear, actionable insights using tools like Power BI.\n\n" +
                "2. <strong>AI Web Development</strong> — Developing web applications with AI-focused features using PHP, JavaScript, and MySQL.\n\n" +
                "You can use the contact form on this portfolio to discuss a project."
        },
        education: {
            keywords: ['education', 'degree', 'university', 'college', 'academic', 'b.com', 'bachelor', 'study', 'studied'],
            response: "Mehwish Qamar holds a <strong>Bachelor of Commerce (B.Com)</strong> degree from the <strong>University of Karachi</strong>.\n\n" +
                "She has also completed professional certifications in Data Analytics & Business Intelligence (DigiSkills.pk), Freelancing (DigiSkills.pk), and AI Web Development (DTAN)."
        },
        university: {
            keywords: ['karachi', 'university of karachi', 'where did', 'where study'],
            response: "Mehwish Qamar studied at the <strong>University of Karachi</strong>, where she completed her <strong>Bachelor of Commerce (B.Com)</strong> degree."
        },
        experience: {
            keywords: ['experience', 'job', 'employment', 'worked', 'company', 'work history'],
            response: "Mehwish Qamar has <strong>1 year of professional experience at Shan Foods</strong> as a <strong>Quality Controller (QC)</strong>.\n\n" +
                "<strong>Responsibilities:</strong>\n" +
                "• Quality checking of products\n" +
                "• Monitoring quality standards\n" +
                "• Maintaining consistency in processes\n" +
                "• Attention to detail in assessments\n" +
                "• Teamwork and collaboration\n" +
                "• Professional responsibility\n\n" +
                "She is also currently engaged in self-directed learning and practice in Data Analytics, Business Intelligence, Power BI, and AI Web Development."
        },
        shanfoods: {
            keywords: ['shan foods', 'shan', 'qc', 'quality controller', 'quality checking'],
            response: "Mehwish Qamar worked at <strong>Shan Foods</strong> as a <strong>Quality Controller (QC)</strong> for <strong>1 year</strong>.\n\n" +
                "<strong>Key responsibilities:</strong>\n" +
                "• Quality checking of products\n" +
                "• Monitoring quality standards\n" +
                "• Maintaining consistency in processes\n" +
                "• Attention to detail in assessments\n" +
                "• Teamwork and collaboration\n" +
                "• Professional responsibility\n\n" +
                "This experience strengthened her analytical thinking and professional discipline, skills she now applies in data analytics and web development."
        },
        certifications: {
            keywords: ['certification', 'certifications', 'certificate', 'certified', 'course', 'courses'],
            response: "Mehwish Qamar holds the following professional certifications:\n\n" +
                "1. <strong>Data Analytics & Business Intelligence</strong> — DigiSkills.pk\n\n" +
                "2. <strong>Freelancing</strong> — DigiSkills.pk\n\n" +
                "3. <strong>AI Web Development</strong> — DTAN\n\n" +
                "These certifications support her professional development in Data Analytics, Business Intelligence, Freelancing, and AI Web Development."
        },
        contact: {
            keywords: ['contact', 'email', 'reach', 'get in touch', 'message', 'how to contact', 'send'],
            response: "You can reach Mehwish Qamar through:\n\n" +
                "<strong>Email:</strong> <a href='mailto:mahwishqamar4@gmail.com'>mahwishqamar4@gmail.com</a>\n\n" +
                "<strong>LinkedIn:</strong> <a href='https://www.linkedin.com/in/mehwish-qamar-133230375' target='_blank' rel='noopener noreferrer'>linkedin.com/in/mehwish-qamar-133230375</a>\n\n" +
                "<strong>GitHub:</strong> <a href='https://github.com/mahwishqamar04' target='_blank' rel='noopener noreferrer'>github.com/mahwishqamar04</a>\n\n" +
                "<strong>Upwork:</strong> <a href='https://www.upwork.com/freelancers/~0160585e54d9a38131' target='_blank' rel='noopener noreferrer'>Upwork Profile</a>\n\n" +
                "You can also use the contact form on this portfolio to send a message directly."
        },
        linkedin: {
            keywords: ['linkedin', 'linkedin profile', 'linkedin url'],
            response: "You can connect with Mehwish Qamar on LinkedIn:\n\n" +
                "<a href='https://www.linkedin.com/in/mehwish-qamar-133230375' target='_blank' rel='noopener noreferrer'>linkedin.com/in/mehwish-qamar-133230375</a>\n\n" +
                "The link will open in a new tab."
        },
        goals: {
            keywords: ['goal', 'goals', 'career', 'ambition', 'future', 'aspiration', 'long term', 'short term', 'where going'],
            response: "<strong>Mehwish Qamar's career ambition</strong> is to become highly skilled in Data Analytics and AI Web Development.\n\n" +
                "<strong>Current focus:</strong> Strengthening practical expertise in Data Analytics, Business Intelligence, Power BI, and AI Web Development through hands-on project practice.\n\n" +
                "<strong>Long-term vision:</strong> To grow into a skilled professional capable of creating valuable data-driven and technology-based solutions for businesses.\n\n" +
                "She is actively working toward this through self-directed learning, building projects, and completing professional certifications."
        },
        achievements: {
            keywords: ['achievement', 'achievements', 'accomplish', 'accomplishment', 'milestone', 'proud'],
            response: "Mehwish Qamar's key professional achievements include:\n\n" +
                "• <strong>3 Professional Certifications</strong> — Data Analytics & Business Intelligence (DigiSkills.pk), Freelancing (DigiSkills.pk), AI Web Development (DTAN)\n\n" +
                "• <strong>4 Practical Projects</strong> — Spanning web development, AI web applications, and Power BI dashboards\n\n" +
                "• <strong>1 Year at Shan Foods</strong> — Professional experience as a Quality Controller\n\n" +
                "• <strong>Continuous Skill Building</strong> — Actively developing expertise in Data Analytics, BI, Power BI, and AI Web Development"
        },
        freelancing: {
            keywords: ['freelanc', 'freelancer', 'freelancing', 'client', 'hire me', 'available'],
            response: "Mehwish Qamar is <strong>open to projects and opportunities</strong>.\n\n" +
                "She holds a <strong>Freelancing certification from DigiSkills.pk</strong> and is available for client-based work.\n\n" +
                "Her services include:\n" +
                "• Data Analysis & Dashboard Creation\n" +
                "• AI Web Development\n\n" +
                "You can find her on <a href='https://www.upwork.com/freelancers/~0160585e54d9a38131' target='_blank' rel='noopener noreferrer'>Upwork</a>, or reach her via email at <a href='mailto:mahwishqamar4@gmail.com'>mahwishqamar4@gmail.com</a> or through the contact form on this portfolio."
        }
    };

    var defaultResponse = "I appreciate your question! I can help you learn about Mehwish Qamar's skills, projects, services, experience, education, certifications, career goals, or contact information.\n\n" +
        "I only share information available on this portfolio — I don't invent details.\n\n" +
        "Try asking:\n" +
        "- What skills does Mehwish Qamar have?\n" +
        "- Tell me about the Quetta Services Hub\n" +
        "- What services does Mehwish Qamar offer?\n" +
        "- What is Mehwish Qamar's education?\n" +
        "- What is Mehwish Qamar's LinkedIn?\n" +
        "- What are Mehwish Qamar's career goals?";

    function findResponse(userInput) {
        var input = userInput.toLowerCase();
        for (var category in portfolioData) {
            var data = portfolioData[category];
            for (var i = 0; i < data.keywords.length; i++) {
                if (input.indexOf(data.keywords[i]) !== -1) {
                    return data.response;
                }
            }
        }
        return defaultResponse;
    }

    function addMessage(message, type) {
        var $body = $('#aiChatBody');
        var $msg = $('<div class="' + type + '-message"></div>');
        $msg.html(message.replace(/\n/g, '<br>'));
        $body.append($msg);
        $body.scrollTop($body[0].scrollHeight);
    }

    function toggleChat() {
        $('#aiChatPanel').toggleClass('active');
    }

    // Event Handlers
    $('#aiFloatBtn').on('click', function() { toggleChat(); });
    $('#aiChatClose').on('click', function() { $('#aiChatPanel').removeClass('active'); });
    $('.btn-ai-assistant').on('click', function(e) { e.preventDefault(); toggleChat(); });

    $('#aiChatForm').on('submit', function(e) {
        e.preventDefault();
        handleUserInput();
    });

    $(document).on('click', '.ai-suggestion-chip', function() {
        var question = $(this).text();
        $('#aiChatInput').val(question);
        if (!$('#aiChatPanel').hasClass('active')) { toggleChat(); }
        handleUserInput();
    });

    function handleUserInput() {
        var $input = $('#aiChatInput');
        var userInput = $input.val().trim();
        if (!userInput) return;
        addMessage(userInput, 'user');
        $input.val('');
        setTimeout(function() {
            var response = findResponse(userInput);
            addMessage(response, 'ai');
        }, 400);
    }

})(jQuery);
