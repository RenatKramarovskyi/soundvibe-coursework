import React from 'react';
import CommentBlock from "../CommentBlock/CommentBlock.tsx";
import cls from './CommentList.module.css'
const CommentList = ({comments, isCommentsLoading}) => {

    return (
        <div className={cls.commentsArea}>
            {
                isCommentsLoading
                    ?  <div>Loading...</div>
                    : comments.map((comment) => (
                            <CommentBlock comment={comment} key={comment.id}/>
                        )
                    )
            }
        </div>
    );
};

export default CommentList;