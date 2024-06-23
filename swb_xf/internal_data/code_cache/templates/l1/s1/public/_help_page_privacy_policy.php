<?php
// FROM HASH: 29e4864740e8913fc940c712f3a32382
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<p><strong>SWB</strong> is committed to safeguarding the privacy of our website users. This condensed
policy outlines how we collect, store, and use your information..</p>

<h2>Information We Collect</h2>
<ul>
	<li><b>Members:</b> When you register, we collect certain personal details for verification.
After verification:</li>
        <ul>
	      <li><b>Admirer:</b> We retain your SWB ID, email, username, security answers, and a
fragment of your phone number.</li>
	      <li><b>Companions:</b> In addition to the above, we store images for age verification
and ensure account exclusivity.</li>
	      <li><b>Communications:</b> Records of communications within SWB, and any
interaction with SWB, are maintained.</li>
	      <li><b>Cookies:</b> We use cookies to enhance user experience. The site may
malfunction if cookies are disabled in your browser.</li>
	      <li><b>Traffic Data:</b> We gather data like IP addresses, geodata, and browser type
for analytical purposes.</li>
	      <li><b>Email Addresses:</b> Used for both marketing and transactional purposes.
Users can opt out from marketing emails.</li>
        </ul>
</ul>

<h2>Usage of Information</h2>
<ul>
	<li>Your <b>SWB</b> ID/username facilitates site access. Security answers aid in account
recovery. Your email address will receive essential notifications and occasional
marketing messages. We actively monitor for any malicious or prohibited
activities.</li>
</ul>

<h2>Sharing Information</h2>
<ul>
	<li><b>SWB</b> keeps your data confidential except under specific circumstances such as
legal obligations or to safeguard the <b>SWB</b> community. Additionally, be cautious
about the details you share with other members as <b>SWB</b> isn\'t responsible for how
they use the shared data.</li>
</ul>

<h2>Retention and Deletion</h2>
<ul>
	<li>Your data is retained as long as you\'re a member. If you desire a full deletion,
submit a detailed request. However, in some situations, especially involving
potential illegal activities, data may be retained for safety reasons</li>
</ul>

<h2>Payment Information</h2>
<ul>
	<li>Payments are processed by third parties; SWB doesn’t access sensitive payment
details. Only basic data, vital for membership management, is shared with us by
payment processors.</li>
</ul>

<h2>Security</h2>
<ul>
	<li><b>SWB</b> uses advanced encryption <b>(128-bit SSL)</b> to secure data. Yet, despite our
best efforts, no system is impenetrable. You acknowledge the risks associated
with online data storage.</li>
</ul>

<h2>Membership Renewals</h2>
<ul>
	<li>Memberships don\'t auto-renew. Near expiry, you\'ll receive a reminder email.</li>
</ul>

<h2>Refunds</h2>
<ul>
	<li>Refunds are not provided. For issues, contact support at <b>"use this email for
customer support the ADMIN email --"</b></li>
</ul>

<h2>External Links</h2>
<ul>
	<li><b>SWB</b> might link to external websites, but we aren\'t responsible for their privacy
practices.</li>
</ul>

<h2>Your Role</h2>
<ul>
	<li>Guard your password and notify us if you suspect unauthorized account activity.
Always log out post sessions, especially on shared devices.</li>
</ul>

<h2>Policy Modifications</h2>
<ul>
	<li>We may occasionally update this policy. Stay updated by revisiting this section.</li>
</ul>

<p>For further clarifications, <b>"<a href="' . $__templater->escape($__vars['xf']['contactUrl']) . '">contact us</a>"</b>.</p>
<p><b>CUSTOMER SUPPORT CONTACT US</b> - use this <b><a href="mailto:debbie@southwestboard.com" >debbie@southwestboard.com</a></b> for now.</p>' . '

' . '

';
	if ($__vars['captcha'] AND $__vars['xf']['options']['includeCaptchaPrivacyPolicy']) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= $__templater->escape($__templater->method($__vars['captcha'], 'getPrivacyPolicy', array()));
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
		<h2>' . 'CAPTCHA privacy policy' . '</h2>
		' . $__compilerTemp1 . '
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);