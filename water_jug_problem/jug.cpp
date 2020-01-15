
#include <bits/stdc++.h>
#include <list>
#define pii pair<int, int>
#define mp make_pair
using namespace std;

void BFS(int a, int b, int target)
{
    // Map is used to store the states, every
    // state is hashed to binary value to
    // indicate either that state is visited
    // before or not
    std::map<pii, int> m;
    bool isSolvable = false;
    vector<pii> path;

    list<pii> q; // queue to maintain states
    q.push_back({ 0, 0 }); // Initialing with initial state

    while (!q.empty()) {

        pii u = q.front(); // current state
        q.pop_front(); // pop off used state
        // if this state is already visited
        if (m[{ u.first, u.second }] == 1)
            continue;

        // doesn't met jug constraints
        if ((u.first > a || u.second > b ||
             u.first < 0 || u.second < 0))
            continue;

        // filling the vector for constructing
        // the solution path
        path.push_back({ u.first, u.second });

        // marking current state as visited
        m[{ u.first, u.second }] = 1;

        if ((u.first == target && u.second == 0)) {
            isSolvable = true;

            // print the solution path
            cout << "\nPath from initial state "
             "to solution state :\n";
            int sz = path.size();
            for (int i = 0; i < sz; i++)
                cout << "(" << path[i].first
                     << ", " << path[i].second << ")\n";
            break;
        }


        // if we have not reached final state
        // then, start developing intermediate
        // states to reach solution state
        if (m.find({ u.first, b }) == m.end()) {
            m[{ u.first, b }] = 0;
	    cout << "fill jug2\n";
            q.push_back({ u.first, b }); // fill Jug2
        }
        if  (m.find({a, u.second}) == m.end()) {
            m[{ a, u.second }] = 0;
            cout << "fill jug1\n";
            q.push_back({ a, u.second }); // fill Jug1
        }

        for (int ap = 0; ap <= max(a, b); ap++) {

            // pour amount ap from Jug2 to Jug1
            int c = u.first + ap;
            int d = u.second - ap;

            // check if this state is possible or not
            if ((c == a || (d == 0)) && (c >= 0 && c <=a && d >=0 && d <=b)) {
                if (m.find({c, d}) == m.end()) {
                    cout << "pour the jug2 into jug1\n"; 
                    q.push_back({ c, d });
                    m[{ c, d}] = 0;
                }
            }

            // Pour amount ap from Jug 1 to Jug2
            c = u.first - ap;
            d = u.second + ap;

            // check if this state is possible or not
            if (((c == 0) || d == b) && (c >= 0 && c <=a && d >=0 && d <=b)) {
                if (m.find({c, d}) == m.end()) {
                    cout << "pour jug1 into jug2\n";
                    q.push_back({ c, d });
                    m[{c ,d}] = 0;
                }
            }
        }

        if (m.find({u.first, 0}) == m.end()) {
            m[{u.first, 0}] = 0;
            q.push_back({ u.first, 0 }); // Empty Jug2
        }


        if (m.find({0, u.second}) == m.end()) {
            m[{0, u.second}] = 0;
            q.push_back({ 0, u.second }); // Empty Jug1
        }


    }

    // No, solution exists if ans=0
    if (!isSolvable)
        cout << "No solution";

//    print close node
    map<pii, int>::iterator close = m.begin();
    cout << "close:\n";
    while (close != m.end()) {
        if (close->second == 1) {
            cout
                    << "<"
                    << close->first.first
                    << ","
                    << close->first.second
                    << ">"
                    << ':'
                    << close->second
                    << "\n";
        }

        close++;
    }

//    print open node
    map<pii, int>::iterator open = m.begin();
    cout << "open:\n";
    while (open != m.end()) {
        if (open->second == 0) {
            cout
                    << "<"
                    << open->first.first
                    << ","
                    << open->first.second
                    << ">"
                    << ':'
                    << open->second
                    << "\n";
        }

        open++;
    }

}

int main()
{
//    target is first jug
    int Jug1 = 4, Jug2 = 3, target = 1;
    cout << "Steps: \n";
    BFS(Jug1, Jug2, target);
    return 0;
}
