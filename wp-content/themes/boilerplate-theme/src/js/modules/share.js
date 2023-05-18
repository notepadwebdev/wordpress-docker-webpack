export default function(el) {

	const url = encodeURIComponent(window.location.href);
	const metaDescriptionContent = document.querySelector(`[property="og:description"]`).getAttribute('content');
	const description = encodeURIComponent(metaDescriptionContent);

	// Twitter.
	const twitterLink = el.querySelector(`.twitter a`);
	twitterLink.setAttribute('href', `http://twitter.com/intent/tweet?text=${description}&url=${url}`);

	// LinkedIn.
	const linkedInLink = el.querySelector(`.linked-in a`);
	linkedInLink.setAttribute('href', `https://www.linkedin.com/sharing/share-offsite/?url=${url}`);

	// Facebook.
	const facebookLink = el.querySelector(`.facebook a`);
	facebookLink.setAttribute('href', `https://www.facebook.com/sharer/sharer.php?u=${url}`);
	
}