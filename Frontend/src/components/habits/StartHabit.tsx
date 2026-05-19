import { MdDownloadDone } from "react-icons/md";

export default function StartHabit({ habitId, refreshData }: { habitId: number; refreshData: () => void }) {
	async function handleStartHabit() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/habit-status-started", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId }),
			});
			const data = await response.json();
			if (data.success) {
				refreshData();
			}
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	return (
		<>
			<button className='action-btn success-action-btn me-2' onClick={handleStartHabit}>
				<MdDownloadDone size={24} />
			</button>
		</>
	);
}
