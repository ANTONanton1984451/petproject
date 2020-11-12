
export const  validation = {
    methods : {
        validatePassword(password) {
            let regexp = new RegExp("[А-Яа-яёЁ]",'gm');
            if(password.length > 255 || password === ''){
                return false;
            }
            return !regexp.test(password);
        },
        validateNickName(nickName) {
            let regexp = new RegExp("^[a-zA-Z0-9.-]+$");
            if(nickName.length > 15 || nickName === ''){
                return false;
            }
            return regexp.test(nickName);
        },
        validateLogin(login) {
            let regexp = new RegExp("^[a-zA-Z0-9]+$");
            if(login.length > 15 || login === ''){
                return  false;
            }
            return regexp.test(login);
        }
    }
}
