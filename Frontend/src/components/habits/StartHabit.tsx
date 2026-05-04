import { Button } from "react-bootstrap";
import { MdDownloadDone } from "react-icons/md";

export default function StartHabit() {
	return (
		<>
			<Button className='bg-success me-2'>
				<MdDownloadDone size={24} />
			</Button>
		</>
	);
}
