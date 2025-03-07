import React, {FC} from 'react';
import {Post} from "../../types/types.ts";
import cls from './PostList.module.css'
import PostItem from "../PostItem/PostItem.tsx";
interface PostListProps {
    posts: Post[]
}

const PostList: FC<PostListProps> = ({posts}) => {
    return (
        <div className={cls.listArea}>
            {
                posts.map((post: Post) =>
                    <PostItem postData={post} key={post.id}/>
                )
            }
        </div>
    );
};

export default PostList;