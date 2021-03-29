/*
 Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
CKEDITOR.addTemplates('default', {
	imagesPath: CKEDITOR.getUrl(
		CKEDITOR.plugins.getPath('templates') + 'templates/images/'
	),
	templates: [
		{
			title: 'code-block',
			image: '1.png',
			html: `<div class="code"><pre class="line-numbers language-" data-start="1" style="counter-reset: linenumber 0;"><code>Lorem ipsum</code></pre></div>`,
		},
		{
			title: 'code-block-full without link',
			image: '3.png',
			html: `<div class="code full"><div class="code__header"><span class="code__header__title">Lorem ipsum</span></div><pre class="line-numbers language-" data-start="1" style="counter-reset: linenumber 0;"><code>Lorem ipsum</code></pre><div class="code__output"><span class="code__output__title">Результат</span> <pre class="code__output__text">Lorem ipsum</pre> </div></div>`,
		},
		{
			title: 'code-block-full with link',
			image: '3+.png',
			html: `<div class="code full"><div class="code__header"><span class="code__header__title">Lorem ipsum</span><a href="https://" class="code__header__cta" data-tooltip="открыть в песочнице" target="_blank"><svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1.40608 14C1.1725 13.9996 0.94306 13.9383 0.740322 13.8223C0.283803 13.5636 0 13.0615 0 12.5163V1.48376C0 0.937074 0.283803 0.436424 0.740322 0.177729C0.947888 0.0584393 1.18369 -0.00290576 1.42307 0.000105775C1.66245 0.00311731 1.89663 0.0703751 2.10113 0.194849L11.5302 5.83895C11.7267 5.96217 11.8887 6.13328 12.001 6.33623C12.1132 6.53918 12.1722 6.76733 12.1722 6.99927C12.1722 7.23122 12.1132 7.45936 12.001 7.66232C11.8887 7.86527 11.7267 8.03638 11.5302 8.15959L2.09961 13.8052C1.89033 13.9317 1.65063 13.9991 1.40608 14V14Z" fill="#212121"></path> </svg></a></div><pre class="line-numbers language-" data-start="1" style="counter-reset: linenumber 0;"><code>Lorem ipsum</code></pre><div class="code__output"><span class="code__output__title">Результат</span> <pre class="code__output__text">Lorem ipsum</pre> </div></div>`,
		},
		{
			title: 'code-block-top without link',
			image: '2.png',
			html: `<div class="code top"><div class="code__header"><span class="code__header__title">Lorem ipsum</span></div><pre class="line-numbers language-" data-start="1" style="counter-reset: linenumber 0;"><code>Lorem ipsum</code></pre></div>`,
		},
		{
			title: 'code-block-top with link',
			image: '2+.png',
			html: `<div class="code top"><div class="code__header"><span class="code__header__title">Lorem ipsum</span><a href="https://" class="code__header__cta" data-tooltip="открыть в песочнице" target="_blank"><svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1.40608 14C1.1725 13.9996 0.94306 13.9383 0.740322 13.8223C0.283803 13.5636 0 13.0615 0 12.5163V1.48376C0 0.937074 0.283803 0.436424 0.740322 0.177729C0.947888 0.0584393 1.18369 -0.00290576 1.42307 0.000105775C1.66245 0.00311731 1.89663 0.0703751 2.10113 0.194849L11.5302 5.83895C11.7267 5.96217 11.8887 6.13328 12.001 6.33623C12.1132 6.53918 12.1722 6.76733 12.1722 6.99927C12.1722 7.23122 12.1132 7.45936 12.001 7.66232C11.8887 7.86527 11.7267 8.03638 11.5302 8.15959L2.09961 13.8052C1.89033 13.9317 1.65063 13.9991 1.40608 14V14Z" fill="#212121"></path> </svg></a></div><pre class="line-numbers language-" data-start="1" style="counter-reset: linenumber 0;"><code>Lorem ipsum</code></pre></div>`,
		},
		{
			title: 'code-block-bottom',
			image: '4.png',
			html:
				'<div class="code bottom"><pre class="line-numbers language-" data-start="1" style="counter-reset: linenumber 0;"><code>Lorem ipsum</code></pre><div class="code__output"><span class="code__output__title">Результат</span> <pre class="code__output__text">Lorem ipsum</pre> </div></div>',
		},
	],
});
