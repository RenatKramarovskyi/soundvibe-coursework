import {FC} from 'react';
import cls from './DefaultButton.module.css'

export enum DefaultButtonVariant {
    SUBMIT = 'submit',
    RESET = 'reset',
    BUTTON = 'button'
}
interface DefaultButtonProps {
    type?: DefaultButtonVariant | undefined,
    onClick?: (event) => void,
    name?: string | undefined,
    children: React.ReactNode
}
const DefaultButton: FC<DefaultButtonProps> =
    ({
        type,
        onClick,
        name,
        children,
        ...props
     }) => {
    return (
        <button
            onClick={onClick}
            type={type}
            name={name}
            {...props}
            className={cls.defaultBtn}
        >
            {children}
        </button>
    );
};

export default DefaultButton;