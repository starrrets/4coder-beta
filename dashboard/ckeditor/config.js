CKEDITOR.editorConfig = function (config) {
	config.toolbarGroups = [
		{ name: 'document', groups: ['mode', 'document', 'doctools'] },
		{ name: 'clipboard', groups: ['clipboard', 'undo'] },
		{
			name: 'editing',
			groups: ['find', 'selection', 'spellchecker', 'editing'],
		},
		{ name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
		{
			name: 'paragraph',
			groups: ['list', 'blocks', 'indent', 'align', 'bidi', 'paragraph'],
		},
		{ name: 'insert', groups: ['insert'] },
		{ name: 'links', groups: ['links'] },
		{ name: 'forms', groups: ['forms'] },
		{ name: 'tools', groups: ['tools'] },
		{ name: 'others', groups: ['others'] },
		{ name: 'styles', groups: ['styles'] },
		{ name: 'colors', groups: ['colors'] },
		{ name: 'about', groups: ['about'] },
	];

	config.removeButtons = 'Cut,Copy,Paste,Outdent,Indent';
	config.stylesSet = [
		{
			name: 'Highlight',
			element: 'span',
			attributes: { class: 'highlighted' },
		},
	];
	config.format_tags = 'p;h3';
	config.allowedContent = true;
};
