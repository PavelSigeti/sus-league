<template>
    <div class="file-upload">
        <h4 @click="open=!open">{{props.document.title}}</h4>
        <div :class="['upload-container', {'upload-container__show': open}]">
            <div class="img-container" v-if="!!path">
                <div class="current-img">
                    <a :href="path" target="_blank">
                        <i class="ri-file-pdf-2-line" v-if="path.includes('pdf')"></i>
                        <img :src="path" alt="image" v-else>
                    </a>
                    <div class="remove-img" v-if="docs && (docs.status === 'edit' || docs.status === 'cancel')" @click="remove"><i class="ri-delete-bin-line"></i></div>
                </div>
            </div>
            <div class="single-img" v-else>
                <div class="form-control-title">{{ props.title }}</div>
                <label for="file" class="label-file" v-if="!url">
                    <div class="file-upload-label">
                        <div class="file-upload-label__select">
                            <i class="ri-folder-upload-line"></i>
                            Выбрать файл
                        </div>
                    </div>
                </label>
                <div
                    class="imagePreviewWrapper"
                    :style="{ 'background-image': `url(${url})` }"
                    v-else
                >
                    <i class="ri-restart-fill"></i>
                </div>
                <input type="file" @change="handleFileUpload" id="file">
                <div class="error-msg" v-if="error">{{ error }}</div>
            </div>
        </div>
        <div class="file-status">
            Статус: {{ status }}
        </div>
    </div>

</template>

<script setup>
import {ref} from 'vue';
import {useStore} from "vuex";
import statusText from "@/utils/statusText";

const store = useStore();
const props = defineProps(['document', 'file']);

const path = ref(props.file ? '/storage' + props.file.path : null);
const status = ref(statusText(props.file ? props.file.status : null));
const docs = ref(props.file ? props.file : null);

const error = ref();
const open = ref(false);
const previewImage = (event) => {
    const input = event.target;
    if (input.files) {
        const reader = new FileReader();
        reader.onload = (e) => {
            url.value = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

const url = ref(null);

const handleFileUpload = async (event) => {
    error.value = null;
    try {
        const allowedFileTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        const maxSize = 4 * 1024 * 1024;
        const file = event.target.files[0];
        if (!allowedFileTypes.includes(file.type)) {
            alert('Не правильный формат файла. Используйте JPEG, PNG, WEBP, или PDF');
            throw new ValidationError("Не правильный формат файла. Используйте JPEG, PNG, WEBP, или PDF");
        }
        if (file.size > maxSize) {
            alert('Файл должен быть меньше 4МБ');
            throw new ValidationError("Файл должен быть меньше 4МБ");
        }
        previewImage(event);
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', props.document.type);
        const ans = await axios.post('/api/user/file', formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        url.value = null;
        path.value = '/storage' + ans.data.file.path;
        status.value = statusText('edit');
        docs.value = ans.data.file;
    } catch(e) {
        console.log(e.message);
        error.value = 'Ошибка при загрузке файлов';
        url.value = null;
    }
};

const remove = async () => {
    try {
        const ans = await axios.delete(`/api/user/file/${props.document.type}`);
        if(ans.data.result === true) {
            status.value = statusText(null);
            path.value = null;
        } else {
            store.dispatch('notification/displayMessage', {
                value: 'Файл не удален',
                type: 'error',
            });
        }
    } catch (e) {
        console.log(e.message);
    }
};

</script>

<style lang="scss">
.current-img {
    width: 250px;
    height: 200px;
    overflow: hidden;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    a {
        color: #242424;
    }
    .ri-file-pdf-2-line {
        font-size: 5em;
    }
}
.remove-img {
    width: 36px;
    height: 36px;
    background-color: rgba(0, 0, 0, .75);
    border-radius: 20px;
    position: absolute;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 15px;
    right: 15px;
    cursor: pointer;
    &:hover {
        box-shadow: inset 0px 0px 0px 2px #fff;
    }
}
.single-img {
    .filepond--wrapper {
        max-width: 300px;
        width: 100%;
        .filepond--credits {
            visibility: hidden;
        }
    }

}
.imagePreviewWrapper {
    height: 200px;
    width: 250px;
    border-radius: 10px;
    background-position: center;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    border:1px solid #ccc;
    box-sizing: border-box;
    i {
        animation: spin 1s infinite linear;
        font-size: 48px;
        color: #242424;
    }
}
.file-upload-label {
    display: flex;
    background-color: #ededed;
    width: 250px;
    height: 200px;
    border-radius: 10px;
    justify-content: center;
    align-items: center;
}
.file-upload-label__select {
    width: 250px;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    color: #222;
    cursor: pointer;
    i {
        font-size: 28px;
    }
}
#file {
    display: none;
}
h4 {
    font-weight: 500;
    margin-bottom: 15px;
    font-size: 1.1em;
}
.file-upload {
    padding: 15px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,.15);
    border-radius: 10px;
    height: fit-content;
}
.file-status {
    margin-top: 10px;
}
.upload-container {
    height: 0;
    overflow: hidden;
    transition: .3s;
    box-shadow: 0px 0px 5px 0px rgba(0,0,0, .15);
    border-radius: 10px;
}
.upload-container__show {
    height: 200px;
    max-height: fit-content;
}
h4 {
    cursor: pointer;
    user-select: none;
    &:hover {
        text-decoration: underline;
    }
}
@keyframes spin {
    from {
       transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
