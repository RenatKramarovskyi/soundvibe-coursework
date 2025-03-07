import {FC} from 'react';
import cls from './DefaultInput.module.css'
export enum InputVariant {
    TEXT = 'text',
    PASSWORD = 'password',
    EMAIL = 'email'
}
interface DefaultInputProps {
    value: string | undefined,
    onChange: (event) => void,
    type: InputVariant,
    placeholder: string,
    name: string,
}

const DefaultInput: FC<DefaultInputProps> =
    ({
        type,
        value,
        onChange,
        placeholder,
        name,
        ...props
     }) => {
    return (
        <input
            type={type}
            value={value}
            placeholder={placeholder}
            onChange={onChange}
            name={name}
            {...props}
            className={cls.inputBox}
        />
    );
};

export default DefaultInput;