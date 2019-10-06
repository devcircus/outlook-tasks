import { isEmpty, has } from 'lodash';

export default {
    methods: {
        isObjectEmpty (obj) {
            return isEmpty(obj);
        },
        objectContains (obj, needle) {
            return has(obj, needle);
        },
    },
}
