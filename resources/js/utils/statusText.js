export default (status) => {
    switch (status) {
        case 'edit':
            return 'На модерации';

        case 'approve':
            return 'Докумнт подтвержден';

        case 'cancel':
            return 'Не прошел модерацию';

        default:
            return 'Файл не загружен'
    }

}
