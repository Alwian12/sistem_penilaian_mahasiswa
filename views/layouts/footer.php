		</main>
	</div>
	<script>
		const sidebar = document.getElementById('appSidebar');
		const backdrop = document.getElementById('mobileBackdrop');
		const toggle = document.getElementById('sidebarToggle');

		const closeSidebar = () => {
			if (!sidebar || !backdrop) return;
			sidebar.classList.remove('is-open');
			backdrop.classList.add('opacity-0');
			window.setTimeout(() => backdrop.classList.add('hidden'), 200);
		};

		toggle?.addEventListener('click', () => {
			backdrop.classList.remove('hidden');
			window.requestAnimationFrame(() => backdrop.classList.remove('opacity-0'));
			sidebar.classList.add('is-open');
		});
		backdrop?.addEventListener('click', closeSidebar);
		document.querySelectorAll('#appSidebar a').forEach((link) => link.addEventListener('click', closeSidebar));
	</script>
</body>
</html>
