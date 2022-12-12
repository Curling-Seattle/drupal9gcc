import Link from "next/link"

export function Footer({ children }) {
  return (
      <footer>
        <div className="container flex items-center justify-between py-6 mx-auto">
          <div className="footercolumn">
	    <b>Learn</b>
            <ul>
	       <li>Curling Basics</li>
	       <li>Equipment</li>
	       <li>History</li>
	    </ul>
          </div>
          <div className="footercolumn">
	    <b>Play</b>
	    <ul>
	       <li>Membership</li>
	       <li>Leagues</li>
	       <li>Juniors</li>
	    </ul>
          </div>
          <div className="footercolumn">
	    <b>Members</b>
	    <ul>
	       <li>Volunteer</li>
	       <li>New Member's Guide</li>
	       <li>Board Minutes</li>
	    </ul>
          </div>
        </div>
        
     </footer>
  )
}
