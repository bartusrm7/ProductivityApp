import { Button } from "react-bootstrap";
import { RiDeleteBin6Line } from "react-icons/ri";

export default function DeleteNote({ noteId }: { noteId: number }) {
	async function handleDeleteNote() {
		try {
			console.log(noteId);
			const jwt = localStorage.getItem("jwt");
			await fetch("http://productivityapp.local/delete-note", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: noteId }),
			});
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	return (
		<>
			<Button className='bg-danger' onClick={handleDeleteNote}>
				<RiDeleteBin6Line size={24} />
			</Button>
		</>
	);
}
