import { useContext, useState } from 'react'
import { Link } from 'react-router-dom'

// Context
import DispatchContext from '../DispatchContext'

const HeaderLogin = () => {
  const [isActiveDropdown, setIsActiveDropdown] = useState(false)
  const appDispatch = useContext(DispatchContext)

  const toggleDropdown = () => setIsActiveDropdown(prev => !prev)

  const handleClick = (e) => {
    e.preventDefault()
    toggleDropdown()
  }

  const handleLogout = () => {
    appDispatch({ type: 'logout' })
    appDispatch({
      type: 'addToast',
      data: {
        title: 'Logout correctly',
        type: 'success',
        message: 'You have been logout.'
      }
    })
  }

  return (
    <li className="nav-item dropdown">
      <a onClick={handleClick} className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i className="fa-solid fa-bars"></i> Menú</a>
      <ul className={'dropdown-menu end-0 ' + (isActiveDropdown && 'show')} aria-labelledby="navbarDropdown">
        <li><a className="dropdown-item" href="#"><i className="fa-solid fa-user"></i> Profile</a></li>
        <li><Link className="dropdown-item" to="/create-post"><i className="fa-solid fa-plus"></i> Create Post</Link></li>
        <li><hr className="dropdown-divider"/></li>
        <li><button onClick={handleLogout} className="dropdown-item" href="#"><i className="fa-solid fa-arrow-right-from-bracket"></i> Logout</button></li>
      </ul>
    </li>
  )
}

export default HeaderLogin
