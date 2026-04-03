document.addEventListener('DOMContentLoaded', () => {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const mdzMessage = document.getElementById('mdzMessage');

    dropzone.addEventListener('click', () => {
        fileInput.click();
    });

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('dragover');
        const files = event.dataTransfer.files;
        if (files.length) {
            handleFiles(files);
        }
    });

    fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        if (files.length) {
            handleFiles(files);
        }
    });

    function handleFiles(files) {
        mdzMessage.style.display = 'none';
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const deleteButton = document.createElement('button');
                    deleteButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        previewContainer.removeChild(previewItem);
                        if (previewContainer.childElementCount === 0) {
                            mdzMessage.style.display = 'block';
                        }
                    });

                    previewItem.appendChild(img);
                    previewItem.appendChild(deleteButton);
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            } else {
                alert('Please upload a valid image file.');
            }
        });
    }
});
