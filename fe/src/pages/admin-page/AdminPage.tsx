import React, {useState} from 'react';
import cls from './AdminPage.module.css'
import {Send} from "lucide-react";


const AdminPage = () => {

    const [commentInput, setCommentInput] = useState('')

    const handleSendComment = async () => {

    }
    return (
        <div className={cls.container}>


            <div className={cls.inputContainer}>
                <div>
                    <input
                        type={"file"}
                    />
                </div>
                <div>
                    <input
                        type="text"
                        value={commentInput}
                        onChange={(e) =>
                            setCommentInput(e.target.value)}
                        placeholder={"news title..."}
                    />
                </div>
                <div>
                    <input
                        type="text"
                        value={commentInput}
                        onChange={(e) =>
                            setCommentInput(e.target.value)}
                        placeholder={"news content"}
                    />
                </div>
                <div>
                    <input
                        type="text"
                        value={commentInput}
                        onChange={(e) =>
                            setCommentInput(e.target.value)}
                    />
                </div>
               <div>
                   <button>
                       <Send size={32} onClick={handleSendComment}/>
                   </button>
               </div>
            </div>
        </div>
    );
};

export default AdminPage;