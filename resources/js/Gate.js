export default class Gate{

    constructor(user){
        this.user = user;
        console.log("------------------------ Gate ");
        console.log("------------------------ user "+this.user);
    }

    isAdmin(){
        return this.user.type === 'admin';
    }

    isUser(){
        return this.user.type === 'user';
    }
    
    isAdminOrUser(){
        if(this.user.type === 'user' || this.user.type === 'admin'){
            return true;
        }
    }
}

