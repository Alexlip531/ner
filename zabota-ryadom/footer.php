<?php
/**
 * Footer шаблон.
 *
 * @package ZabotaRyadom
 */
?>
</div><!-- #primary -->

<!-- TRUST BAR -->
<section class="trust-bar">
	<div class="container trust-grid">

		<div class="trust-item">
			<div class="trust-icon">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none">
					<path d="m12 2 3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7l3-7Z" fill="white"/>
				</svg>
			</div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Контроль качества', 'zabota-ryadom' ); ?></h4>
				<p><?php esc_html_e( 'Мы проверяем учреждения и сиделок лично', 'zabota-ryadom' ); ?></p>
			</div>
		</div>

		<div class="trust-item">
			<div class="trust-icon">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none">
					<path d="M5 13l4 4L19 7" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Без скрытых платежей', 'zabota-ryadom' ); ?></h4>
				<p><?php esc_html_e( 'Никаких комиссий и переплат', 'zabota-ryadom' ); ?></p>
			</div>
		</div>

		<div class="trust-item">
			<div class="trust-icon">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none">
					<circle cx="12" cy="12" r="9" stroke="white" stroke-width="2"/>
					<path d="M12 7v5l3 2" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Помощь 24/7', 'zabota-ryadom' ); ?></h4>
				<p><?php esc_html_e( 'Поддержка и консультации в любое время', 'zabota-ryadom' ); ?></p>
			</div>
		</div>

		<div class="trust-item">
			<div class="trust-icon">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none">
					<path d="M3 21V8l9-5 9 5v13M9 21v-6h6v6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
			<div class="trust-text">
				<h4><?php esc_html_e( 'Более 12 500 семей', 'zabota-ryadom' ); ?></h4>
				<p><?php esc_html_e( 'уже нашли подходящий уход с нашей помощью', 'zabota-ryadom' ); ?></p>
			</div>
		</div>

	</div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
	<div class="container footer-grid">

		<div class="footer-col footer-col-about">
			<div class="footer-logo">
				<svg width="36" height="36" viewBox="0 0 36 36" fill="none">
					<path d="M18 31.5C18 31.5 4.5 22.5 4.5 13.5C4.5 9.358 7.858 6 12 6C14.5 6 16.5 7.5 18 9.5C19.5 7.5 21.5 6 24 6C28.142 6 31.5 9.358 31.5 13.5C31.5 22.5 18 31.5 18 31.5Z" fill="#10B981"/>
					<rect x="13" y="16" width="10" height="9" rx="1" fill="white"/>
				</svg>
				<span class="footer-logo-text">Забота рядом</span>
			</div>
			<p class="footer-tagline"><?php esc_html_e( 'Помогаем найти лучший уход для ваших близких. Пансионаты, сиделки и уход на дому с проверенными отзывами.', 'zabota-ryadom' ); ?></p>
		</div>

		<div class="footer-col">
			<h4><?php esc_html_e( 'Услуги', 'zabota-ryadom' ); ?></h4>
			<ul>
				<li><a href="#"><?php esc_html_e( 'Пансионаты', 'zabota-ryadom' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Сиделки', 'zabota-ryadom' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Реабилитация', 'zabota-ryadom' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Уход на дому', 'zabota-ryadom' ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col">
			<h4><?php esc_html_e( 'Компания', 'zabota-ryadom' ); ?></h4>
			<ul>
				<li><a href="#"><?php esc_html_e( 'О проекте', 'zabota-ryadom' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Блог', 'zabota-ryadom' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Контакты', 'zabota-ryadom' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Партнёрам', 'zabota-ryadom' ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col footer-col-contacts">
			<h4><?php esc_html_e( 'Контакты', 'zabota-ryadom' ); ?></h4>
			<ul>
				<li class="contact-phone"><a href="tel:+74951234567">+7 (495) 123-45-67</a></li>
				<li class="contact-email"><a href="mailto:info@zabota-ryadom.ru">info@zabota-ryadom.ru</a></li>
				<li class="contact-address"><?php esc_html_e( 'Москва, ул. Примерная, 1', 'zabota-ryadom' ); ?></li>
			</ul>
			<div class="footer-socials">
				<a href="#" aria-label="Telegram"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22 4 2 11l5 2 2 6 3-4 5 4 5-15Z"/></svg></a>
				<a href="#" aria-label="WhatsApp"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.5A10 10 0 1 0 12 2Zm5.4 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.2-.7-2.7-1.1-4.4-3.9-4.6-4.1-.1-.2-1-1.4-1-2.6 0-1.2.6-1.8.8-2 .2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 1.9c.1.2.1.4 0 .5l-.3.5-.4.4c-.1.1-.3.3-.1.5.2.3.8 1.3 1.7 2 .9.7 1.7 1 2 1.1.2.1.4.1.5-.1l.6-.8c.2-.2.4-.2.6-.1l1.8.9c.3.1.4.2.5.3.1.2.1.6-.1 1.2Z"/></svg></a>
				<a href="#" aria-label="VK"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8 16.5c-5 0-8.2-3.4-8.3-9.1h2.5c.1 4.2 2 6 3.4 6.3V7.4h2.4v3.6c1.4-.2 2.9-1.8 3.4-3.6h2.4c-.4 2.2-2 3.8-3.1 4.5 1.1.5 3 2 3.7 4.6h-2.6c-.6-1.8-2-3.2-3.8-3.4v3.4h-.3Z"/></svg></a>
			</div>
		</div>

	</div>

	<div class="footer-bottom">
		<div class="container">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Забота рядом. <?php esc_html_e( 'Все права защищены.', 'zabota-ryadom' ); ?></p>
			<div class="footer-links">
				<a href="#"><?php esc_html_e( 'Политика конфиденциальности', 'zabota-ryadom' ); ?></a>
				<a href="#"><?php esc_html_e( 'Пользовательское соглашение', 'zabota-ryadom' ); ?></a>
			</div>
		</div>
	</div>
</footer>

<button type="button" class="scroll-top" aria-label="<?php esc_attr_e( 'Наверх', 'zabota-ryadom' ); ?>">
	<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
		<path d="M10 16V4M4 10l6-6 6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
