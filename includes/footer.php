<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                <span class="footer-brand">Mehwish Qamar</span>
            </div>
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <div class="footer-social">
                    <a href="mailto:mahwishqamar4@gmail.com" title="Email"><i class="bi bi-envelope-fill"></i></a>
                    <a href="https://www.linkedin.com/in/mehwish-qamar-133230375" target="_blank" rel="noopener noreferrer" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="https://github.com/mahwishqamar04" target="_blank" rel="noopener noreferrer" title="GitHub"><i class="bi bi-github"></i></a>
                    <a href="https://www.upwork.com/freelancers/~0160585e54d9a38131" target="_blank" rel="noopener noreferrer" title="Upwork"><i class="bi bi-briefcase-fill"></i></a>
                </div>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <p class="footer-copy">&copy; <?php echo date('Y'); ?> Mehwish Qamar. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- AI Assistant Floating Button -->
<button class="ai-float-btn" id="aiFloatBtn" title="AI Portfolio Assistant">
    <i class="bi bi-robot"></i>
</button>

<!-- AI Assistant Chat Panel -->
<div class="ai-chat-panel" id="aiChatPanel">
    <div class="ai-chat-header">
        <div class="d-flex align-items-center">
            <i class="bi bi-robot me-2"></i>
            <span>AI Portfolio Assistant</span>
        </div>
        <button class="ai-chat-close" id="aiChatClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="ai-chat-body" id="aiChatBody">
        <div class="ai-message">
            <p>Hello! I'm Mehwish Qamar's AI Portfolio Assistant. Ask me anything about Mehwish Qamar's skills, projects, services, experience, or how to get in touch.</p>
        </div>
    </div>
    <div class="ai-chat-input">
        <form id="aiChatForm">
            <div class="input-group">
                <input type="text" class="form-control" id="aiChatInput" placeholder="Ask about Mehwish Qamar's portfolio..." autocomplete="off">
                <button class="btn btn-ai-send" type="submit"><i class="bi bi-send-fill"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Back to Top -->
<a href="#home" class="back-to-top" id="backToTop" title="Back to Top">
    <i class="bi bi-chevron-up"></i>
</a>

<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>

<!-- Main JS -->
<script src="assets/js/main.js"></script>

<!-- AI Assistant JS -->
<script src="assets/js/ai-assistant.js"></script>

</body>
</html>
