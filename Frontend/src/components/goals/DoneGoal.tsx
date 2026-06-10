import { MdDownloadDone } from "react-icons/md";

export default function DoneGoal({ goalId, refreshData }: { goalId: number; refreshData: () => void }) {
	async function handleDoneGoal(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/done-goal", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: goalId }),
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
			<button className='action-btn success-action-btn me-2' onClick={handleDoneGoal}>
				<MdDownloadDone size={24} />
			</button>
		</>
	);
}
