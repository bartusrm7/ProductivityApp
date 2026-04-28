import { Button } from "react-bootstrap";
import { MdDownloadDone } from "react-icons/md";

export default function TaskDone({ taskId }: { taskId: number }) {
	async function handleTaskDone(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/task-done", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
			});
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	return (
		<>
			<Button className='bg-success' onClick={handleTaskDone}>
				<MdDownloadDone />
			</Button>
		</>
	);
}
