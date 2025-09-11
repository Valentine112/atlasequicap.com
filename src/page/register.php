<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlasequicap Broker</title>
    <link rel="stylesheet" href="form/style.css">
</head>

<body>
    <div class="neural-background">
        <div class="neural-node"></div>
        <div class="neural-node"></div>
        <div class="neural-node"></div>
        <div class="neural-node"></div>
        <div class="neural-node"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="ai-glow"></div>

            <div class="login-header">
                <div class="ai-logo">
                    <div class="logo-core">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="logo-rings">
                        <div class="ring ring-1"></div>
                        <div class="ring ring-2"></div>
                        <div class="ring ring-3"></div>
                    </div>
                </div>
                <h1>Atlasequicap</h1>
                <p>Join us for the first time</p>
            </div>

            <form class="login-form" id="loginForm" novalidate data-type="login" onsubmit="event.preventDefault()">
                <div id="message"></div>
                <div class="smart-field" data-field="fullname">
                    <div class="field-background"></div>
                    <input type="text" id="fullname" name="text" required>
                    <label for="fullname">Fullname</label>
                    <div class="ai-indicator">
                        <div class="ai-pulse"></div>
                    </div>
                    <div class="field-completion"></div>
                    <span class="error-message" id="fullnameError"></span>
                </div>
                <div class="smart-field" data-field="email">
                    <div class="field-background"></div>
                    <input type="email" id="email" name="email" required autocomplete="email">
                    <label for="email">Email Address</label>
                    <div class="ai-indicator">
                        <div class="ai-pulse"></div>
                    </div>
                    <div class="field-completion"></div>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="smart-field" data-field="phone">
                    <div class="field-background"></div>
                    <input type="text" id="phone" name="phone" required>
                    <label for="phone">Phone number</label>
                    <div class="ai-indicator">
                        <div class="ai-pulse"></div>
                    </div>
                    <div class="field-completion"></div>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="smart-filed my-4" data-field="country">
                    <select id="country" name="country" class="form-control" style="background-color: #141F2C; border: 1px solid #3A80F3; color: #fff;">
                        <option value="" data-number="">Select Country</option>
                        <option value="Afghanistan" data-number="+93">🇦🇫 Afghanistan</option>
                        <option value="Albania" data-number="+355">🇦🇱 Albania</option>
                        <option value="Algeria" data-number="+213">🇩🇿 Algeria</option>
                        <option value="Andorra" data-number="+376">🇦🇩 Andorra</option>
                        <option value="Angola" data-number="+244">🇦🇴 Angola</option>
                        <option value="Argentina" data-number="+54">🇦🇷 Argentina</option>
                        <option value="Armenia" data-number="+374">🇦🇲 Armenia</option>
                        <option value="Australia" data-number="+61">🇦🇺 Australia</option>
                        <option value="Austria" data-number="+43">🇦🇹 Austria</option>
                        <option value="Azerbaijan" data-number="+994">🇦🇿 Azerbaijan</option>
                        <option value="Bahrain" data-number="+973">🇧🇭 Bahrain</option>
                        <option value="Bangladesh" data-number="+880">🇧🇩 Bangladesh</option>
                        <option value="Belarus" data-number="+375">🇧🇾 Belarus</option>
                        <option value="Belgium" data-number="+32">🇧🇪 Belgium</option>
                        <option value="Benin" data-number="+229">🇧🇯 Benin</option>
                        <option value="Bhutan" data-number="+975">🇧🇹 Bhutan</option>
                        <option value="Bolivia" data-number="+591">🇧🇴 Bolivia</option>
                        <option value="Bosnia and Herzegovina" data-number="+387">🇧🇦 Bosnia and Herzegovina</option>
                        <option value="Botswana" data-number="+267">🇧🇼 Botswana</option>
                        <option value="Brazil" data-number="+55">🇧🇷 Brazil</option>
                        <option value="Brunei" data-number="+673">🇧🇳 Brunei</option>
                        <option value="Bulgaria" data-number="+359">🇧🇬 Bulgaria</option>
                        <option value="Burkina Faso" data-number="+226">🇧🇫 Burkina Faso</option>
                        <option value="Burundi" data-number="+257">🇧🇮 Burundi</option>
                        <option value="Cambodia" data-number="+855">🇰🇭 Cambodia</option>
                        <option value="Cameroon" data-number="+237">🇨🇲 Cameroon</option>
                        <option value="Canada" data-number="+1">🇨🇦 Canada</option>
                        <option value="Cape Verde" data-number="+238">🇨🇻 Cape Verde</option>
                        <option value="Central African Republic" data-number="+236">🇨🇫 Central African Republic</option>
                        <option value="Chad" data-number="+235">🇹🇩 Chad</option>
                        <option value="Chile" data-number="+56">🇨🇱 Chile</option>
                        <option value="China" data-number="+86">🇨🇳 China</option>
                        <option value="Colombia" data-number="+57">🇨🇴 Colombia</option>
                        <option value="Comoros" data-number="+269">🇰🇲 Comoros</option>
                        <option value="Costa Rica" data-number="+506">🇨🇷 Costa Rica</option>
                        <option value="Croatia" data-number="+385">🇭🇷 Croatia</option>
                        <option value="Cuba" data-number="+53">🇨🇺 Cuba</option>
                        <option value="Cyprus" data-number="+357">🇨🇾 Cyprus</option>
                        <option value="Czech Republic" data-number="+420">🇨🇿 Czech Republic</option>
                        <option value="Denmark" data-number="+45">🇩🇰 Denmark</option>
                        <option value="Djibouti" data-number="+253">🇩🇯 Djibouti</option>
                        <option value="Dominica" data-number="+1">🇩🇲 Dominica</option>
                        <option value="Dominican Republic" data-number="+1">🇩🇴 Dominican Republic</option>
                        <option value="Ecuador" data-number="+593">🇪🇨 Ecuador</option>
                        <option value="Egypt" data-number="+20">🇪🇬 Egypt</option>
                        <option value="El Salvador" data-number="+503">🇸🇻 El Salvador</option>
                        <option value="Equatorial Guinea" data-number="+240">🇬🇶 Equatorial Guinea</option>
                        <option value="Eritrea" data-number="+291">🇪🇷 Eritrea</option>
                        <option value="Estonia" data-number="+372">🇪🇪 Estonia</option>
                        <option value="Ethiopia" data-number="+251">🇪🇹 Ethiopia</option>
                        <option value="Fiji" data-number="+679">🇫🇯 Fiji</option>
                        <option value="Finland" data-number="+358">🇫🇮 Finland</option>
                        <option value="France" data-number="+33">🇫🇷 France</option>
                        <option value="Gabon" data-number="+241">🇬🇦 Gabon</option>
                        <option value="Gambia" data-number="+220">🇬🇲 Gambia</option>
                        <option value="Georgia" data-number="+995">🇬🇪 Georgia</option>
                        <option value="Germany" data-number="+49">🇩🇪 Germany</option>
                        <option value="Ghana" data-number="+233">🇬🇭 Ghana</option>
                        <option value="Greece" data-number="+30">🇬🇷 Greece</option>
                        <option value="Guatemala" data-number="+502">🇬🇹 Guatemala</option>
                        <option value="Guinea" data-number="+224">🇬🇳 Guinea</option>
                        <option value="Guinea-Bissau" data-number="+245">🇬🇼 Guinea-Bissau</option>
                        <option value="Guyana" data-number="+592">🇬🇾 Guyana</option>
                        <option value="Haiti" data-number="+509">🇭🇹 Haiti</option>
                        <option value="Honduras" data-number="+504">🇭🇳 Honduras</option>
                        <option value="Hungary" data-number="+36">🇭🇺 Hungary</option>
                        <option value="Iceland" data-number="+354">🇮🇸 Iceland</option>
                        <option value="India" data-number="+91">🇮🇳 India</option>
                        <option value="Indonesia" data-number="+62">🇮🇩 Indonesia</option>
                        <option value="Iran" data-number="+98">🇮🇷 Iran</option>
                        <option value="Iraq" data-number="+964">🇮🇶 Iraq</option>
                        <option value="Ireland" data-number="+353">🇮🇪 Ireland</option>
                        <option value="Israel" data-number="+972">🇮🇱 Israel</option>
                        <option value="Italy" data-number="+39">🇮🇹 Italy</option>
                        <option value="Jamaica" data-number="+1">🇯🇲 Jamaica</option>
                        <option value="Japan" data-number="+81">🇯🇵 Japan</option>
                        <option value="Jordan" data-number="+962">🇯🇴 Jordan</option>
                        <option value="Kazakhstan" data-number="+7">🇰🇿 Kazakhstan</option>
                        <option value="Kenya" data-number="+254">🇰🇪 Kenya</option>
                        <option value="Kuwait" data-number="+965">🇰🇼 Kuwait</option>
                        <option value="Kyrgyzstan" data-number="+996">🇰🇬 Kyrgyzstan</option>
                        <option value="Laos" data-number="+856">🇱🇦 Laos</option>
                        <option value="Latvia" data-number="+371">🇱🇻 Latvia</option>
                        <option value="Lebanon" data-number="+961">🇱🇧 Lebanon</option>
                        <option value="Liberia" data-number="+231">🇱🇷 Liberia</option>
                        <option value="Libya" data-number="+218">🇱🇾 Libya</option>
                        <option value="Lithuania" data-number="+370">🇱🇹 Lithuania</option>
                        <option value="Luxembourg" data-number="+352">🇱🇺 Luxembourg</option>
                        <option value="Madagascar" data-number="+261">🇲🇬 Madagascar</option>
                        <option value="Malawi" data-number="+265">🇲🇼 Malawi</option>
                        <option value="Malaysia" data-number="+60">🇲🇾 Malaysia</option>
                        <option value="Maldives" data-number="+960">🇲🇻 Maldives</option>
                        <option value="Mali" data-number="+223">🇲🇱 Mali</option>
                        <option value="Malta" data-number="+356">🇲🇹 Malta</option>
                        <option value="Mauritania" data-number="+222">🇲🇷 Mauritania</option>
                        <option value="Mauritius" data-number="+230">🇲🇺 Mauritius</option>
                        <option value="Mexico" data-number="+52">🇲🇽 Mexico</option>
                        <option value="Moldova" data-number="+373">🇲🇩 Moldova</option>
                        <option value="Monaco" data-number="+377">🇲🇨 Monaco</option>
                        <option value="Mongolia" data-number="+976">🇲🇳 Mongolia</option>
                        <option value="Montenegro" data-number="+382">🇲🇪 Montenegro</option>
                        <option value="Morocco" data-number="+212">🇲🇦 Morocco</option>
                        <option value="Mozambique" data-number="+258">🇲🇿 Mozambique</option>
                        <option value="Myanmar" data-number="+95">🇲🇲 Myanmar</option>
                        <option value="Namibia" data-number="+264">🇳🇦 Namibia</option>
                        <option value="Nepal" data-number="+977">🇳🇵 Nepal</option>
                        <option value="Netherlands" data-number="+31">🇳🇱 Netherlands</option>
                        <option value="New Zealand" data-number="+64">🇳🇿 New Zealand</option>
                        <option value="Nicaragua" data-number="+505">🇳🇮 Nicaragua</option>
                        <option value="Niger" data-number="+227">🇳🇪 Niger</option>
                        <option value="Nigeria" data-number="+234">🇳🇬 Nigeria</option>
                        <option value="North Korea" data-number="+850">🇰🇵 North Korea</option>
                        <option value="Norway" data-number="+47">🇳🇴 Norway</option>
                        <option value="Oman" data-number="+968">🇴🇲 Oman</option>
                        <option value="Pakistan" data-number="+92">🇵🇰 Pakistan</option>
                        <option value="Panama" data-number="+507">🇵🇦 Panama</option>
                        <option value="Papua New Guinea" data-number="+675">🇵🇬 Papua New Guinea</option>
                        <option value="Paraguay" data-number="+595">🇵🇾 Paraguay</option>
                        <option value="Peru" data-number="+51">🇵🇪 Peru</option>
                        <option value="Philippines" data-number="+63">🇵🇭 Philippines</option>
                        <option value="Poland" data-number="+48">🇵🇱 Poland</option>
                        <option value="Portugal" data-number="+351">🇵🇹 Portugal</option>
                        <option value="Qatar" data-number="+974">🇶🇦 Qatar</option>
                        <option value="Romania" data-number="+40">🇷🇴 Romania</option>
                        <option value="Russia" data-number="+7">🇷🇺 Russia</option>
                        <option value="Rwanda" data-number="+250">🇷🇼 Rwanda</option>
                        <option value="Saudi Arabia" data-number="+966">🇸🇦 Saudi Arabia</option>
                        <option value="Senegal" data-number="+221">🇸🇳 Senegal</option>
                        <option value="Serbia" data-number="+381">🇷🇸 Serbia</option>
                        <option value="Singapore" data-number="+65">🇸🇬 Singapore</option>
                        <option value="Slovakia" data-number="+421">🇸🇰 Slovakia</option>
                        <option value="Slovenia" data-number="+386">🇸🇮 Slovenia</option>
                        <option value="South Africa" data-number="+27">🇿🇦 South Africa</option>
                        <option value="South Korea" data-number="+82">🇰🇷 South Korea</option>
                        <option value="Spain" data-number="+34">🇪🇸 Spain</option>
                        <option value="Sri Lanka" data-number="+94">🇱🇰 Sri Lanka</option>
                        <option value="Sudan" data-number="+249">🇸🇩 Sudan</option>
                        <option value="Sweden" data-number="+46">🇸🇪 Sweden</option>
                        <option value="Switzerland" data-number="+41">🇨🇭 Switzerland</option>
                        <option value="Syria" data-number="+963">🇸🇾 Syria</option>
                        <option value="Taiwan" data-number="+886">🇹🇼 Taiwan</option>
                        <option value="Tanzania" data-number="+255">🇹🇿 Tanzania</option>
                        <option value="Thailand" data-number="+66">🇹🇭 Thailand</option>
                        <option value="Togo" data-number="+228">🇹🇬 Togo</option>
                        <option value="Tunisia" data-number="+216">🇹🇳 Tunisia</option>
                        <option value="Turkey" data-number="+90">🇹🇷 Turkey</option>
                        <option value="Uganda" data-number="+256">🇺🇬 Uganda</option>
                        <option value="Ukraine" data-number="+380">🇺🇦 Ukraine</option>
                        <option value="United Arab Emirates" data-number="+971">🇦🇪 United Arab Emirates</option>
                        <option value="United Kingdom" data-number="+44">🇬🇧 United Kingdom</option>
                        <option value="United States" data-number="+1">🇺🇸 United States</option>
                        <option value="Uruguay" data-number="+598">🇺🇾 Uruguay</option>
                        <option value="Uzbekistan" data-number="+998">🇺🇿 Uzbekistan</option>
                        <option value="Venezuela" data-number="+58">🇻🇪 Venezuela</option>
                        <option value="Vietnam" data-number="+84">🇻🇳 Vietnam</option>
                        <option value="Yemen" data-number="+967">🇾🇪 Yemen</option>
                        <option value="Zambia" data-number="+260">🇿🇲 Zambia</option>
                        <option value="Zimbabwe" data-number="+263">🇿🇼 Zimbabwe</option>
                    </select>
                </div>

                <div class="smart-field" data-field="password">
                    <div class="field-background"></div>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <label for="password">Password</label>
                    <button type="button" class="smart-toggle" id="passwordToggle" aria-label="Toggle password visibility" onclick="togglePassword(this)">
                        <svg class="toggle-show" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M9 3.75c-3.15 0-5.85 1.89-7.02 4.72a.75.75 0 000 .56c1.17 2.83 3.87 4.72 7.02 4.72s5.85-1.89 7.02-4.72a.75.75 0 000-.56C14.85 5.64 12.15 3.75 9 3.75zM9 12a3 3 0 110-6 3 3 0 010 6z" fill="currentColor"/>
                        </svg>
                        <svg class="toggle-hide" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M3.53 2.47a.75.75 0 00-1.06 1.06l11 11a.75.75 0 101.06-1.06l-2.82-2.82c.8-.67 1.5-1.49 2.04-2.42a.75.75 0 000-.56C12.58 4.84 10.89 3.75 9 3.75c-.69 0-1.36.1-2 .29L3.53 2.47zM7.974 5.847L10.126 8a1.5 1.5 0 01-2.126-2.126l-.026-.027z" fill="currentColor"/>
                        </svg>
                    </button>
                    <div class="ai-indicator">
                        <div class="ai-pulse"></div>
                    </div>
                    <div class="field-completion"></div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                <div class="form-options">
                    <!-- <label class="smart-checkbox">
                        <input type="checkbox" id="remember" name="remember">
                        <span class="checkbox-ai">
                            <div class="checkbox-core"></div>
                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none">
                                <path d="M1 5l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="checkbox-text">Remember this session</span>
                    </label> -->
                    <a href="forgot" class="neural-link">Reset password</a>
                </div>

                <button type="submit" class="neural-button" onclick="log(this, 'register')">
                    <div class="button-bg"></div>
                    <span class="button-text">Signup now</span>
                    <div class="button-loader">
                        <div class="neural-spinner">
                            <div class="spinner-segment"></div>
                            <div class="spinner-segment"></div>
                            <div class="spinner-segment"></div>
                        </div>
                    </div>
                    <div class="button-glow"></div>
                </button>
            </form>

            <div class="auth-separator">
                <div class="separator-line"></div>
                <span class="separator-text">or</span>
                <div class="separator-line"></div>
            </div>

            <div class="signup-section">
                <span>Already have an account? </span>
                <a href="login" class="neural-signup">Login now</a>
            </div>

            <div class="success-neural" id="successMessage">
                <div class="success-core">
                    <div class="success-rings">
                        <div class="success-ring"></div>
                        <div class="success-ring"></div>
                        <div class="success-ring"></div>
                    </div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3>Neural Link Established</h3>
                <p>Accessing your AI workspace...</p>
            </div>
        </div>
    </div>

    <?php include "src/template/quick-notice.php"; ?>

    <script src="form/shared/js/form-utils.js"></script>
    <script src="form/script.js"></script>
    <script src="src/assets/js/main.js"></script>
</body>

</html>