# CS/COE 1520 Project 1

## Goal:
To gain experience building interactive web applications through the use of JavaScript and the DOM.

## High-level description:
You will be implementing the game of Battleship to be played with the web browser.
For this version, you will assume that only one user is viewing the page that presents your game at a time ("hotseat" multiplayer, e.g., the two players are using a laptop that they pass back and forth between turns).
Details of the game can be found here:  [https://en.wikipedia.org/wiki/Battleship_(game)](https://en.wikipedia.org/wiki/Battleship_(game))
Your game will be built on a 10x10 grids, with rows labeled 1-10 and columns labeled A-J.
We will assume that players will place 3 game pieces:  an aircraft carrier (5 spaces long), a battleship (4 spaces long), and a submarine (3 spaces long).
These pieces can be placed either horizontally or vertically.

## Specifications:
1. When the page is loaded, your game should begin by asking the first player for her name (for example, lets assume that the first player is Alice), and a string representing her ship placements.
	* Your game should be flexible in accepting the syntax of a ship placement string.  Specifically, it should accept strings of the following forms as equivalent, with each resulting in an aircraft carrier placed on A1, A2, A3, A4, A5; a battleship at B6, C6, D6, E6; and a submarine at H3, I3, J3:
		* `A:A1-A5;B:B6-E6; S:H3-J3`
		* `A(A1-A5); B(B6-E6); S(H3-J3);`
		* `B(B6-E6);S(H3-J3);A(A1-A5)`
1. After gathering the first player's name and ship placement, your game should ask the second player for his name and ship placement (for example, let's assume the second player is Bob).
1. With this starting info in hand, your game should begin the first player's turn
	* At the start of a turn, your game should indicate whose turn it is (e.g., `Click OK to begin Alice's turn`).
	* After the turn information is dismissed, your game should render 2 10x10 grids, one clearly labeled as the current player's ship placements, the other clearly labeled as the current player's target (i.e., information about the other player's ship placements).
		* Both grids should have a light blue background.
		* At the start of the first player's first turn, the target grid will be completely empty, and their grid will show only the location of the first player's ships
			* On the current player's grid, each space occupied by part of an aircraft carrier should contain an `A`, each space occupied by part of a battleship should contain a `B`, each space occupied by part of a submarine should contain an `S`.
	* The player is then free to click a space in the target grid to "fire" at.
		* Clicks on their own grid should have no effect.
		* The game should produce an overlay to inform the player whether the shot was a hit or a miss.
			* The CSS [z-index](https://developer.mozilla.org/en-US/docs/Web/CSS/z-index) property may prove helpful in displaying this overlay.
			* If the player clicks on a space where no part of any of the other player's ships was placed, the shot is a miss, and the space should be colored white.
			* If some part of one of the other player's ships was placed in the clicked space, the shot is a hit, and the space should be colored red.
				* If this hit represents the final portion of a ship (i.e., the 5th hit on an aircraft carrier, the 4th hit on a battleship, or the 3rd hit on a submarine), the player should be notified as to which of the other player's ships they have sunk.
	* After the player has clicked on their target space and the hit or miss has been reported, the player's turn is ended and the other player's turn should begin via a notification (e.g., `Click to begin Bob's turn`).
		* Note that this will require "blanking" the page to prevent Bob from seeing Alice's ship placement (and vice-versa), and will require a rearranging of the page after the notification is dismissed (e.g., so that Bob's target and ship placement grids appear in the positions where Alice's had been previously).
1. At any point in time, a player's target grid should display all the hits (red spaces) and misses (white spaces) that they have fired, while their ship placement grid shows all of the hits and misses their opponents have fired along with their own ship placements.
1. The game continues until all of one player's ships have been sunk, at which point the other player wins the game.
	* Once a winner has been established, your game should compute the winner's score:
		* Score is computed as:  24 - (2 points for each hit against them)
			* -10 points for having the aircraft carrier sunk
			* -8 points for having the battleship sunk
			* -6 points for having the submarine sunk
			* If Alice won the game while Bob was able to sink her submarine (3 hits) and land a single hit on her battle ship, but did not hit her aircraft carrier at all, Alice score would be 16
				* 24 - (2 * 4)
		* Your game should keep track of the names and scores of the 10 players with the highest scores in local storage.
			* If 10 players have all achieved the max score (24), no further updates to local storage are required.
1. For this project, you are not allowed to use any extra JavaScript libraries. You must implement all of this functionality via direct DOM manipulation.

## Submission Guidelines:
* **DO NOT SUBMIT** any IDE package files.
* You must name the main page for your game `battleship.html`, and place it in the root directory of your repository.
* You must fill out `info_sheet.txt`.
* Be sure to remember to push the latest copy of your code back to your GitHub repository before the the assignment is due.  At the deadline, the repositories will automatically be copied for grading.  Whatever is present in your GitHub repository at that time will be considered your submission for this assignment.

## Additional Notes/Hints:
* This project will require extensive manipulation of the DOM to enable the game to run as described.
* Your game does not need to persist across page refreshes. It is acceptable for a page refresh to restart the game.
	* The top scores must persist across refreshes in local storage, however.
* While you are not going to be heavily graded on the style and design of your game, it should be presented in a clear and readable manner.
* Your project submissions will be tested using a recent version of Google Chrome.

## Grading Rubric:
| Feature | Points
| ------- | ------:
| Turns proceed as described | 20%
| Game boards are displayed properly | 25%
| Event handling is processed as described | 15%
| Ship placement strings are correctly parsed | 15%
| Score saving works appropriately | 15%
| Clear and readable presentation | 5%
| Submission/info sheet | 5%
