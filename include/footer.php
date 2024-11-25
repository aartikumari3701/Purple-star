<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Navigation Links -->
            <div class="col-lg-6">
                <nav class="footer_nav_container d-flex flex-sm-row flex-column align-items-center justify-content-lg-start justify-content-center text-center">
                    <ul class="footer_nav">
                        <li><a href="#" title="Read our latest blog posts">Blog</a></li>
                        <li><a href="#" title="Get answers to common questions">FAQs</a></li>
                        <li><a href="contact.php" title="Reach out to us">Contact Us</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Social Media Links -->
            <div class="col-lg-6">
                <div class="footer_social d-flex flex-row align-items-center justify-content-lg-end justify-content-center">
                    <ul>
                        <li>
                            <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Skype">
                                <i class="fa fa-skype" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Pinterest">
                                <i class="fa fa-pinterest" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="footer_nav_container">
                    <div class="cr">
                        &copy; <span id="current-year"></span> All Rights Reserved. Made with 
                        <i class="fa fa-heart-o" aria-hidden="true"></i> by 
                        <a href="#" title="Visit Purple">Purple</a> &amp; distributed by 
                        <a href="#" target="_blank" rel="noopener noreferrer" title="Learn more about Syed Mustafa">Syed Mustafa</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Add this script at the bottom of your HTML -->
<script>
    // Update footer year dynamically
    document.getElementById('current-year').textContent = new Date().getFullYear();
</script>
