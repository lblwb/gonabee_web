(function setVHVariables() {
	console.log('------ variablse ----------')

	const set = () => {
		const getVh = () => {
		return document.documentElement.clientHeight * 0.01;
	}
		document.documentElement.style.setProperty(`--vh`, `${getVh()}px`);
	}

	set();

	document.addEventListener('DOMContentLoaded', set);
	window.addEventListener('orientationchange', set);
})();