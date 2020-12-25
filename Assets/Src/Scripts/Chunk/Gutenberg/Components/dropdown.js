import { Dropdown } from 'semantic-ui-react'

const MultiSelect = (props) => {
    if (props.multiple) {
        return <Dropdown
            placeholder={props.placeholder}
            fluid
            multiple
            selection
            options={props.options}
        />
    } else {
        return <Dropdown
            placeholder={props.placeholder}
            fluid
            selection
            options={props.options}
        />
    }
}

export default MultiSelect