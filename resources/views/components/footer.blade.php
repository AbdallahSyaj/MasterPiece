<!-- Footer Component (resources/views/components/footer.blade.php) -->
<footer class="bg-gray-800 text-white pt-12 pb-6">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Column -->
            <div>
                <h3 class="text-xl font-bold mb-4">med<span class="text-primary-400">Xpert</span></h3>
                <p class="text-gray-400 mb-4">Connecting patients with the best healthcare providers in Jordan. Book appointments online 24/7.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">Find a Doctor</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">View Clinics</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">Medical Specialties</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">How It Works</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">For Doctors</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">Help Center</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">FAQs</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">Terms of Service</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200">Contact Us</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-400"></i>
                        <span>King Hussein Business Park, Amman, Jordan</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-3 text-primary-400"></i>
                        <span>+962 6 000 0000</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-primary-400"></i>
                        <span>info@medxpert.jo</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-10 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; 2025 medXpert. All rights reserved.</p>
                <div class="mt-4 md:mt-0">
                    <img src="{{ asset('images/payment-methods.png') }}" alt="Payment Methods" class="h-8">
                </div>
            </div>
        </div>
    </div>
</footer>