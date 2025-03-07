import React, {FC, useEffect} from 'react';
import {Comment} from "../../../../types/types.ts";
import cls from './CommentBlock.module.css'
import { User } from "lucide-react";
import dayjs from "dayjs";
import {replace, useNavigate} from "react-router-dom";
interface CommentBlockProps {
    comment: Comment
}
const CommentBlock: FC<CommentBlockProps> = ({comment}) => {

    const navigate = useNavigate();

    return (
        <div
            className={cls.commentBlock}
            onClick={() => navigate(`/post/${comment.postId}`, {replace: true})}
        >
            <div className={cls.photoBlock}>
                <User size={32} color={'#000'}/>
            </div>
            <div className={cls.contentBlock}>
                <div className={cls.author}>{comment.author.username}</div>
                <div className={cls.content}>{comment.content}</div>
                <div className={cls.createDate}>
                    {dayjs(comment.createdAt).format("MM-DD-YYYY hh:mm")}
                </div>
            </div>
        </div>
    );
};

export default CommentBlock;