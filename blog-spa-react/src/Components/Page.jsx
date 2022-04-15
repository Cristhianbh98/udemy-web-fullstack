import { useEffect } from 'react'

const Page = ({ title, children }) => {
  useEffect(() => {
    document.title = `${title} | Blog App`
    window.scrollTo(0, 0)
  }, [title])

  return (
    <div className="container">
      {children}
    </div>
  )
}

export default Page
