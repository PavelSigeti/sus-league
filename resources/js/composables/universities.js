import {ref} from 'vue';
import axios from "axios";

export default function useUniversities() {
        const universities = ref();
        const getUniversities = async () => {
            try {
                const response = await axios.get('/api/universities');
                universities.value = response.data;
            } catch (e) {
                console.log(e.message);
            }
        }
    return { universities, getUniversities };
}
