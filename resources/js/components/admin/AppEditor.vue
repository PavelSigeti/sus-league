<template>
    <div class="tiptap">
        <div class="tiptap-menu" v-if="editor">
            <div @click="editor.chain().focus().toggleBold().run()" :disabled="!editor.can().chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                <i class="ri-bold"></i>
            </div>
            <div @click="editor.chain().focus().toggleItalic().run()" :disabled="!editor.can().chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
                <i class="ri-italic"></i>
            </div>
            <div @click="editor.chain().focus().toggleStrike().run()" :disabled="!editor.can().chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
                <i class="ri-strikethrough"></i>
            </div>
            <div @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'is-active': editor.isActive('underline') }">
                <i class="ri-underline"></i>
            </div>
            <div @click="setLink">
                <i class="ri-link"></i>
            </div>
            <div @click="editor.chain().focus().unsetLink().run()" :disabled="!editor.isActive('link')">
                <i class="ri-link-unlink"></i>
            </div>
            <div @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">
                <i class="ri-h-2"></i>
            </div>
            <div @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">
                <i class="ri-h-3"></i>
            </div>
            <div @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }">
                <i class="ri-h-4"></i>
            </div>
            <div @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">
                <i class="ri-list-unordered"></i>
            </div>
            <div @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor.isActive('orderedList') }">
                <i class="ri-list-ordered"></i>
            </div>
            <div @click="editor.chain().focus().setHorizontalRule().run()">
                <i class="ri-separator"></i>
            </div>
            <div @click="editor.chain().focus().undo().run()" :disabled="!editor.can().chain().focus().undo().run()">
                <i class="ri-arrow-go-back-line"></i>
            </div>
            <div @click="editor.chain().focus().redo().run()" :disabled="!editor.can().chain().focus().redo().run()">
                <i class="ri-arrow-go-forward-line"></i>
            </div>
            <div @click="addImage"><i class="ri-image-fill"></i></div>
            <div
                @click="table = table ? false : true"
                :class="{selected: table === true}"
            ><i class="ri-table-fill"></i></div>
        </div>

        <div class="tiptap-table" v-if="table">
            <div @click="editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()">
                <i class="ri-table-line"></i>
            </div>
            <div @click="editor.chain().focus().addColumnBefore().run()">
                <i class="ri-insert-column-left"></i>
            </div>
            <div @click="editor.chain().focus().addColumnAfter().run()">
                <i class="ri-insert-column-right"></i>
            </div>
            <div @click="editor.chain().focus().deleteColumn().run()">
                <i class="ri-delete-column"></i>
            </div>
            <div @click="editor.chain().focus().addRowBefore().run()">
                <i class="ri-insert-row-top"></i>
            </div>
            <div @click="editor.chain().focus().addRowAfter().run()">
                <i class="ri-insert-row-bottom"></i>
            </div>
            <div @click="editor.chain().focus().deleteRow().run()">
                <i class="ri-delete-row"></i>
            </div>
            <div @click="editor.chain().focus().mergeCells().run()">
                <i class="ri-merge-cells-horizontal"></i>
            </div>
            <div @click="editor.chain().focus().splitCell().run()">
                <i class="ri-split-cells-horizontal"></i>
            </div>
            <div @click="editor.chain().focus().toggleHeaderColumn().run()">
                <i class="ri-layout-left-2-fill"></i>
            </div>
            <div @click="editor.chain().focus().toggleHeaderRow().run()">
                <i class="ri-layout-top-2-fill"></i>
            </div>
            <div @click="editor.chain().focus().deleteTable().run()">
                <i class="ri-delete-bin-2-line"></i>
            </div>
        </div>

        <editor-content
            class="tiptap-content"
            :editor="editor"
        />
    </div>
</template>

<script>
import {ref} from 'vue';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';

import Table from '@tiptap/extension-table';
import TableCell from '@tiptap/extension-table-cell';
import TableHeader from '@tiptap/extension-table-header';
import TableRow from '@tiptap/extension-table-row';

import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

export default {
    name: "AppEditor",
    components: {
        EditorContent,
    },
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            type: String,
            default: ''
        },
    },
    setup(props, { emit }) {
        const table = ref(false);

        const editor = useEditor({
            extensions: [
                StarterKit, Image, Link, Underline,
                Table.configure({
                    resizable: true,
                }),
                TableRow,
                TableHeader,
                TableCell,
            ],
            content: props.modelValue,
            onUpdate: ({editor}) => {
                emit('update:modelValue', editor.getHTML())
            }
        });

        const addImage = () => {
            const url = window.prompt('URL')
            if (url) {
                editor.value.chain().focus().setImage({ src: url }).run()
            }
        };

        const clear = () => {
            editor.value.commands.clearContent();
        };

        const setLink = () => {
            const previousUrl = editor.value.getAttributes('link').href
            const url = window.prompt('URL', previousUrl)
            // cancelled
            if (url === null) {
                return;
            }
            // empty
            if (url === '') {
                editor.value
                    .chain()
                    .focus()
                    .extendMarkRange('link')
                    .unsetLink()
                    .run()

                return
            }
            // update link
            editor.value
                .chain()
                .focus()
                .extendMarkRange('link')
                .setLink({ href: url })
                .run()
        };

        return {
            editor, addImage, setLink,
            table, clear,
        }
    }
}
</script>

<style scoped lang="scss">
    @import '@/utils/remixicon/remixicon.css';

    .tiptap {
        border: 3px solid #242424;
        border-radius: 10px;
    }
    .tiptap-menu, .tiptap-table {
        display: flex;
        border-bottom: 3px solid #242424;
        align-items: center;
        padding: 2px 0px;
        div {
            height: 36px;
            width: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            cursor: pointer;
            transition: .2s;
            margin: 0px 2px;
            &:hover {
                background-color: #111;
                i {
                    color: #fff;
                }
            }
        }
        i {
            font-size: 20px;
            font-weight: 500;
            color: #242424;
        }
    }
    .selected {
        background-color: #555;
        i {
            color: #fff;
        }
    }
    .tiptap-content {
        min-height: 100px;
        padding: 10px;
    }
</style>
