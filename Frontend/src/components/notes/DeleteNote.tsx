import { RiDeleteBin6Line } from "react-icons/ri";

export default function DeleteNote({ noteId, refreshData }: { noteId: number; refreshData: () => void }) {
	async function handleDeleteNote() {
		try {
			const jwt = localStorage.getItem("jwt");
			await fetch("http://productivityapp.local/delete-note", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: noteId }),
			});
			refreshData();
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	return (
		<>
			<button className='action-btn delete-action-btn' onClick={handleDeleteNote}>
				<RiDeleteBin6Line size={24} />
			</button>
		</>
	);
}
