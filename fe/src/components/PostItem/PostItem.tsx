import React, {FC} from 'react';
import {Post} from "../../types/types.ts";
import cls from './PostItem.module.css'
import {replace, useNavigate} from "react-router-dom";
interface PostItemProps {
    postData: Post;
}

const PostItem: FC<PostItemProps> = ({postData}) => {

    let navigate = useNavigate();

    return (
        <div className={cls.postBox}>
            <div className={cls.imageArea}>
                <img src="https://random.imagecdn.app/400/400" alt="random-pic"/>
            </div>
            <div className={cls.contentArea}>
                <div>
                    <div className={cls.title} onClick={() => {navigate(`/post/${postData.id}`, {replace: true})}}>{postData.title} </div>
                    <div className={cls.article}>{postData.content}</div>
                </div>
                <div className={cls.postFooter}>
                    <div className={cls.categoryLink} onClick={() => {navigate(`/category/${postData.category}`, {replace: true})}}>{postData.category}</div>
                    <div>Views: {postData.views}</div>
                </div>
            </div>
        </div>
    );
};

export default PostItem;